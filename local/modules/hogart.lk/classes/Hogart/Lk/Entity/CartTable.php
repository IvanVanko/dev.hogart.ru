<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 12/09/16
 * Time: 16:28
 */

namespace Hogart\Lk\Entity;


use Bitrix\Catalog\ProductTable;
use Bitrix\Main\Entity\DatetimeField;
use Bitrix\Main\Entity\EnumField;
use Bitrix\Main\Entity\Event;
use Bitrix\Main\Entity\EventResult;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Entity\ScalarField;
use Bitrix\Main\Type\DateTime;
use Hogart\Lk\Field\GuidField;
use Hogart\Lk\Helper\Template\Account;
use Hogart\Lk\Helper\Template\Error;
use Hogart\Lk\Helper\Template\FlashError;
use Hogart\Lk\Helper\Template\FlashWarning;
use Hogart\Lk\Helper\Template\MessageFactory;
use Ramsey\Uuid\Uuid;

class CartTable extends AbstractEntity
{
    const ITEM_TYPE_GOODS = 'goods';
    const ITEM_TYPE_PROMO = 'promo';

    /**
     * Returns DB table name for entity
     *
     * @return string
     */
    public static function getTableName()
    {
        return "h_cart";
    }

    /**
     * Returns entity map definition
     */
    public static function getMap()
    {
        return [
            new GuidField("guid_id", [
                'primary' => true
            ]),
            new IntegerField("account_id"),
            new ReferenceField("account", __NAMESPACE__ . "\\AccountTable", ["=this.account_id" => "ref.id"]),
            new EnumField("item_type", [
                'values' => [
                    self::ITEM_TYPE_GOODS,
                    self::ITEM_TYPE_PROMO
                ],
                'default_value' => self::ITEM_TYPE_GOODS
            ]),
            new IntegerField("contract_id", ['default_value' => 0]),
            new ReferenceField("contract", __NAMESPACE__ . "\\ContractTable", ["=this.contract_id" => "ref.id"]),
            new GuidField("store_guid", ['default_value' => '']),
            new ReferenceField("store", __NAMESPACE__ . "\\StoreTable", ["=this.store_guid" => "ref.XML_ID"]),
            new DatetimeField("created_at"),
            new DatetimeField("updated_at")
        ];
    }

    public static function getAccountCarts($account_id)
    {
        $filter['=account_id'] = $account_id;
        return self::getList([
            'filter' => $filter,
        ])->fetchAll();
    }

    /**
     * @param null $account_id
     * @param null $cart_id
     * @return array
     */
    public static function getAccountCartList($account_id = null, $cart_id = null)
    {
        $filter= [];
        if (null !== $account_id) {
            $filter['=account_id'] = $account_id;
        }
        if (null !== $cart_id) {
            $filter['=guid_id'] = $cart_id;
        }

        if (empty($filter)) return [];

        $carts = self::getList([
            'filter' => $filter,
            'select' => [
                '*',
                'c_' => 'contract',
                's_' => 'store',
                'company_' => 'contract.company',
            ],
            'order' => [
                'item_type' => 'ASC',
                'contract_id' => 'ASC',
                'store_guid' => 'ASC'
            ]
        ])->fetchAll();
        $group_id = \CCatalogGroup::GetList([], ['BASE' => 'y'])->Fetch()['ID'];
        $currencies = array_reduce(\CStorage::getVar('HOGART.CURRENCIES'), function ($result, $item) {
            $result[$item['CURRENCY']] = $item;
            return $result;
        }, []);

        $stores = StoreTable::getList([
            'filter' => [
                '=ACTIVE' => true
            ],
        ])->fetchAll();

        $accountStores = array_reduce(AccountStoreRelationTable::getByAccountId($account_id), function ($result, $store) {
            $result[$store['ID']] = $store;
            return $result;
        }, []);

        $carts = array_map(function ($cart) use($group_id, $currencies, $stores, $accountStores) {
            $cart['currency'] = $currencies[$cart['c_currency_code']];
            $cart['items'] = CartItemTable::getList([
                'select' => [
                    '*',
                    '' => 'item',
                    'ELEMENT_CODE' => 'item.CODE',
                    'url' => 'item.IBLOCK.DETAIL_PAGE_URL'
                ],
                'filter' => [
                    '=cart_id' => $cart['guid_id'],
                ],
                'order' => [
                    'item_group' => 'ASC',
                    'item_group_position' => 'ASC'
                ]
            ])->fetchAll();

            $prices = [];
            $discounts = [];
            $cart['measures'] = [];
            $itemsId = [];
            foreach ($cart['items'] as &$item) {
                /** @var \Bitrix\Main\Type\DateTime $price_ttl */
                $price_ttl = clone($item['created_at']);
                $price_ttl->add("+" . $item['price_ttl'] . " second");
                $is_valid_cart_price = (bool)max(0, $price_ttl->getTimestamp() - (new \Bitrix\Main\Type\DateTime())->getTimestamp());
                $price = GetCatalogProductPrice($item['item_id'], $group_id);
                $item['price'] = $is_valid_cart_price ? $item['price'] : $price['PRICE'];
                if (!empty($cart['currency']) && $cart['currency']['BASE'] == 'N') {
                    $item['price'] /= $cart['currency']['CURRENT_BASE_RATE'];
                    $item['price'] = round($item['price'], 2);
                }
                $prices[$item['item_id']] = $item['price'];
                $discounts[$item['item_id']] = $item['discount'];
                $item['url'] = \CIBlock::ReplaceDetailUrl($item['url'], $item, false, 'E');
                $itemsId[] = $item['XML_ID'];
            }

            if (!empty($cart['store_guid'])) {
                $store_amount = array_reduce(StoreAmountTable::getList([
                    'filter' => [
                        '=item_guid' => $itemsId
                    ]
                ])->fetchAll(), function ($result, $item) {
                    $result[$item['item_id']][$item['store_guid']] = $item;
                    $result[$item['item_id']]['__stock'] += $item['stock'];
                    $result[$item['item_id']]['__in_reserve'] += $item['in_reserve'];
                    $result[$item['item_id']]['__in_transit'] += $item['in_transit'];
                    return $result;
                }, []);
            }

            if (!empty($cart['items'])) {
                $items = array_reduce($cart['items'], function ($result, $item) { $result[$item['ID']] = $item; return $result; }, []);
                \CIBlockElement::GetPropertyValuesArray($items, CATALOG_IBLOCK_ID, ['ID' => array_keys($items)], ['CODE' => ['sku', 'days_till_receive']]);

                $products = ProductTable::getList([
                    'filter' => [
                        '@ID' => array_keys($items)
                    ]
                ])->fetchAll();


                $products = array_reduce($products, function ($result, $item) { $result[$item['ID']] = $item; return $result; }, []);
                $prices = CompanyDiscountTable::preparePricesByContract($cart['contract_id'], $prices, $discounts);

                foreach ($cart['items'] as &$item) {
                    $item['props'] = $items[$item['item_id']];
                    $item['product'] = $products[$item['item_id']];
                    $cart['measures'][] = $item['product']['MEASURE'];

                    $store_amount[$item['item_id']][$cart['store_guid']] = !empty($store_amount[$item['item_id']][$cart['store_guid']]) ? $store_amount[$item['item_id']][$cart['store_guid']] : [];

                    $item['STORE_AMOUNT'] = (int)$store_amount[$item['item_id']][$cart['store_guid']]['stock'];
                    $item['PREV_POS_AMOUNT'] = (int)$store_amount[$item['item_id']][$cart['store_guid']]['PREV_AMOUNT'];
                    $item['NEGATIVE_AMOUNT'] = (int)max(0, $item['count'] - $store_amount[$item['item_id']][$cart['store_guid']]['stock']);

                    $plus_prev_amount = (int)min($item['count'], $store_amount[$item['item_id']][$cart['store_guid']]['stock']);
                    $store_amount[$item['item_id']]['__stock'] -= $plus_prev_amount;
                    $store_amount[$item['item_id']][$cart['store_guid']]['PREV_AMOUNT'] += $plus_prev_amount;
                    $store_amount[$item['item_id']][$cart['store_guid']]['stock'] = (int)max(0, $store_amount[$item['item_id']][$cart['store_guid']]['stock'] - $item['count']);

                    $item['STORE_RESERVE'] = (int)$store_amount[$item['item_id']][$cart['store_guid']]['in_reserve'];
                    $item['STORE_TRANSIT'] = (int)$store_amount[$item['item_id']][$cart['store_guid']]['in_transit'];
                    $item['STORE_ALL_AMOUNT'] = (int)$store_amount[$item['item_id']]['__stock'];

                    if (isset($prices[$item['item_id']])) {
                        $item['discount'] = $prices[$item['item_id']];
                        $cart['total'] += ($total = $item['discount']['price'] * $item['count']);
                    } else {
                        $cart['total'] += ($total = $item['price'] * $item['count']);
                    }
                    $cart['item_group_totals']['money'][$item['item_group']] += $total;
                    $cart['item_group_totals']['volume'][$item['item_group']] += (round($item['product']['WIDTH'] * $item['product']['LENGTH'] * $item['product']['HEIGHT'] / pow(1000, 3) * $item['count'], 2));
                    $cart['item_group_totals']['weight'][$item['item_group']] += round($item['product']['WEIGHT'] * $item['count'], 2);
                    $cart['items_count'] += $item['count'];
                }

                $cart['items'] = array_reduce($cart['items'], function ($result, $item) { $result[$item['item_group']][] = $item; return $result; }, []);
            }
            return $cart;
        }, $carts);

        return null !== $cart_id ? reset($carts) : $carts;
    }

    public static function addItemToCartBySku($cart_id, $sku, $count = 1, $item_group = '')
    {
        if (!defined('CATALOG_IBLOCK_ID')) {
            new Error("Не определено значение CATALOG_IBLOCK_ID");
            return false;
        }
        $item = \CIBlockElement::GetList([], ['IBLOCK_ID' => CATALOG_IBLOCK_ID, 'PROPERTY_sku' => $sku])->Fetch();
        if (empty($item)) {
            new Error(vsprintf("Товар с артикулом <b>%s</b> не найден", [$sku]));
            return false;
        }

        if (!empty($cart_id)) {
            $cart = self::getByPrimary($cart_id)->fetch();
        }

        $result = CartItemTable::addItem(
            $cart['account_id'] ? : Account::getAccountId(),
            $item['ID'],
            (int)$count,
            (string)$item_group,
            (int)$cart['contract_id'],
            (string)$cart['store_guid']
        );
        if (!$result->getId()) {
            foreach ($result->getErrors() as $error) {
                new Error($error->getMessage());
            }
            return false;
        }
        return $item;
    }

    /**
     * @param $cart_id
     * @param $contract_id
     * @return bool
     */
    public static function changeContract($cart_id, $contract_id)
    {
        $cart = self::getByPrimary($cart_id)->fetch();
        $items = CartItemTable::getByCartId($cart_id);
        foreach ($items as $item) {
            CartItemTable::addItem(
                $cart['account_id'],
                $item['item_id'],
                $item['count'],
                $item['item_group'],
                (int)$contract_id,
                $cart['store_guid']
            );
            CartItemTable::delete($item['guid_id']);
        }

        return true;
    }

    /**
     * @param $cart_id
     * @param $store_id
     * @return bool
     */
    public static function changeStore($cart_id, $store_id)
    {
        $cart = self::getByPrimary($cart_id)->fetch();
        $items = CartItemTable::getByCartId($cart_id);
        foreach ($items as $item) {
            CartItemTable::addItem(
                $cart['account_id'],
                $item['item_id'],
                $item['count'],
                $item['item_group'],
                $cart['contract_id'],
                $store_id
            );
            CartItemTable::delete($item['guid_id']);
        }

        return true;
    }

    public static function deleteItems($cart_id, $items_id)
    {
        foreach ($items_id as $item_id) {
            CartItemTable::delete($item_id);
        }
        self::reorderItems($cart_id);
        return true;
    }

    public static function deleteNoStockItems($cart_id)
    {
        global $APPLICATION;
        $cart = CartTable::getByPrimary(['guid_id' => $cart_id])->fetch();
        $cart = self::getAccountCartList($cart['account_id'], $cart_id);

        foreach ($cart['items'] as $item_group => $items) {
            $APPLICATION->RestartBuffer();
            foreach ($items as $item) {
                if ($item['NEGATIVE_AMOUNT'] > 0) {
                    $res_count = $item['count'] - $item['NEGATIVE_AMOUNT'];
                    if ($res_count > 0) {
                        CartItemTable::update($item['guid_id'], [
                            'cart_id' => $item['cart_id'],
                            'count' => $res_count
                        ]);
                    } else {
                        CartItemTable::delete($item['guid_id']);
                    }
                }
            }
        }
        return true;
    }

    public static function reorderItems($cart_id, $item_group = null)
    {
        $items_groups = array_reduce(CartItemTable::getByCartId($cart_id, $item_group), function ($result, $item) {
            $result[$item['item_group']][] = $item;
            return $result;
        }, []);
        foreach ($items_groups as $item_group => $items) {
            foreach ($items as $pos => $item) {
                CartItemTable::update($item['guid_id'], [
                    'cart_id' => $cart_id,
                    'item_group_position' => ($pos + 1)
                ]);
            }
        }

        return true;
    }

    /**
     * @param $cart_id
     * @param $new_group
     * @param null $old_item_group
     * @param array $ids
     * @param bool $is_copy
     * @param int $contract_id
     * @param string $store_id
     * @param string $item_type
     * @return bool
     */
    public static function changeCategory(
        $cart_id,
        $new_group,
        $old_item_group = null,
        $ids = [],
        $is_copy = false,
        $contract_id = 0,
        $store_id = '',
        $item_type = self::ITEM_TYPE_GOODS
    )
    {
        $new_group = mb_strtoupper(trim((string)$new_group));
        $cart = self::getByPrimary($cart_id)->fetch();

        $items = CartItemTable::getByCartId($cart_id);
        foreach ($items as $item) {
            if (null !== $old_item_group && $old_item_group != $item['item_group']) continue;
            if (!empty($ids) && !in_array($item['guid_id'], $ids)) continue;
            CartItemTable::addItem(
                $cart['account_id'],
                $item['item_id'],
                $item['count'],
                $new_group,
                $contract_id ? : $cart['contract_id'],
                $store_id ? : $cart['store_guid']
            );
            if (!$is_copy) {
                CartItemTable::delete($item['guid_id']);
            }
        }

        self::reorderItems($cart_id);

        return true;
    }

    public static function getUUID($account_id, $contract_id = 0, $store_guid = '', $item_type = self::ITEM_TYPE_GOODS)
    {
        return Uuid::uuid5(Uuid::NAMESPACE_OID, implode('|', [
            $account_id,
            $contract_id,
            $store_guid,
            $item_type,
        ]))->toString();
    }

    public static function getAllItemGroupsByAccount($account_id)
    {
        return CartItemTable::getList([
            'filter' => [
                'cart.account_id' => $account_id
            ],
            'select' => [
                'item_group'
            ],
            'group' => [
                'item_group'
            ]
        ])->fetchAll();
    }

    public static function changeOrder($cart_id, $ids = [])
    {
        $items = CartItemTable::getByCartId($cart_id);
        foreach ($items as $item) {
            if (!isset($ids[$item['guid_id']])) continue;
            CartItemTable::update($item['guid_id'], [
                'cart_id' => $cart_id,
                'item_group_position' => $ids[$item['guid_id']]
            ]);
        }
        return true;
    }

    public static function changeCount($cart_id, $item_id, $count)
    {
        $item = CartItemTable::getByPrimary($item_id)->fetch();
        list($new_count, $default_count) = CartItemTable::getDefaultCount($item['item_id'], $count);
        if ($new_count != $count) {
            new FlashWarning(vsprintf("Количество товара должно быть кратно <b>%s</b>", $default_count));
        }
        CartItemTable::update($item_id, [
            'cart_id' => $cart_id,
            'count' => $new_count
        ]);
    }

    public static function changeDiscount($cart_id, $item_id, $discount)
    {
        $item = CartItemTable::getByPrimary($item_id)->fetch();
        $cart = self::getByPrimary($cart_id)->fetch();
        $max_discount = CompanyDiscountTable::getDiscountByContractAndItem($cart['contract_id'], $item['item_id']);
        if ($max_discount < $discount) {
            new FlashError(vsprintf("Скидка не может превышать <b>%.2f%%</b>", floatval($max_discount)));
            return false;
        }
        return CartItemTable::update($item_id, [
            'cart_id' => $cart_id,
            'discount' => floatval($discount)
        ]);
    }

    public static function setMaxDiscounts($cart_id)
    {
        $cart = CartTable::getByPrimary(['guid_id' => $cart_id])->fetch();
        $cart = self::getAccountCartList($cart['account_id'], $cart_id);

        foreach ($cart['items'] as $item_group => $items) {
            foreach ($items as $item) {
                CartItemTable::update($item['guid_id'], [
                    'cart_id' => $item['cart_id'],
                    'discount' => $item['discount']['max_discount']
                ]);
            }
        }
        return true;
    }

    public static function onBeforeAdd(Event $event)
    {
        $result = new EventResult();
        $fields = $event->getParameter('fields');
        // set fields with default values
        foreach (static::getEntity()->getFields() as $field)
        {
            if ($field instanceof ScalarField && !array_key_exists($field->getName(), $fields))
            {
                $defaultValue = $field->getDefaultValue();

                if ($defaultValue !== null)
                {
                    $fields[$field->getName()] = $field->getDefaultValue();
                }
            }
        }

        $result->modifyFields([
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
            'guid_id' => self::getUUID($fields['account_id'],
                $fields['item_type'],
                $fields['contract_id'],
                $fields['store_guid'])
        ]);
        return $result;
    }

    public static function onBeforeDelete(Event $event)
    {
        $id = $event->getParameter('id')['guid_id'];
        CartItemTable::deleteByCart($id);
    }
}
