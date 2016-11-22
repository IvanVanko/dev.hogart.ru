<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 01/08/16
 * Time: 15:11
 */

namespace Hogart\Lk\Entity;


use Bitrix\Catalog\ProductTable;
use Bitrix\Main\Entity\BooleanField;
use Bitrix\Main\Entity\DatetimeField;
use Bitrix\Main\Entity\EnumField;
use Bitrix\Main\Entity\Event;
use Bitrix\Main\Entity\EventResult;
use Bitrix\Main\Entity\FloatField;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Entity\TextField;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\UI\PageNavigation;
use Hogart\Lk\Exchange\RabbitMQ\Exchange\OrderExchange;
use Hogart\Lk\Exchange\SOAP\Request\Order;
use Hogart\Lk\Field\GuidField;
use Hogart\Lk\Helper\Template\FlashError;
use Hogart\Lk\Helper\Template\FlashSuccess;

/**
 * Таблица Заказы
 * @package Hogart\Lk\Entity
 */
class OrderTable extends AbstractEntity
{
    /** Тип - Продажа */
    const TYPE_SALE = 1;
    /** Тип - Рекламная продукция */
    const TYPE_PROMO = 2;

    /** Статус - Новый */
    const STATUS_NEW = 0;
    /** Статус - В работе */
    const STATUS_IN_WORK = 1;
    /** Статус - Закрыт */
    const STATUS_FINISHED = 2;

    /** Состояние - Нормальное */
    const STATE_NORMAL = 1;
    /** Состояние - Черновик */
    const STATE_DRAFT = 2;
    /** Состояние - В архиве */
    const STATE_ARCHIVE = 3;

    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return "h_order";
    }

    /**
     * {@inheritDoc}
     */
    public static function getMap()
    {
        return [
            new IntegerField("id", [
                'primary' => true,
                "autocomplete" => true
            ]),
            new GuidField("guid_id"),
            new StringField("number"),
            new DatetimeField("order_date"),
            new IntegerField("contract_id"),
            new ReferenceField("contract", __NAMESPACE__ . "\\ContractTable", ["=this.contract_id" => "ref.id"]),
            new EnumField("type", [
                'values' => [
                    self::TYPE_SALE,
                    self::TYPE_PROMO
                ]
            ]),
            new EnumField("status", [
                'values' => [
                    self::STATUS_NEW,
                    self::STATUS_IN_WORK,
                    self::STATUS_FINISHED,
                ],
                'default_value' => self::STATUS_NEW,
            ]),
            new EnumField("state", [
                'values' => [
                    self::STATE_NORMAL,
                    self::STATE_DRAFT,
                    self::STATE_ARCHIVE,
                ],
                'default_value' => self::STATE_NORMAL,
            ]),
            new GuidField("store_guid"),
            new ReferenceField("store", __NAMESPACE__ . "\\StoreTable", ["=this.store_guid" => "ref.XML_ID"]),
            new IntegerField("account_id"),
            new ReferenceField("account", __NAMESPACE__ . "\\AccountTable", ["=this.account_id" => "ref.id"]),
            new IntegerField("staff_id"),
            new ReferenceField("staff", __NAMESPACE__ . "\\StaffTable", ["=this.staff_id" => "ref.id"]),
            new TextField("note"),
            new BooleanField("sale_granted"),
            new FloatField("sale_max_money"),
            new BooleanField("perm_reserve"),
            new BooleanField("is_active")
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected static function getIndexes()
    {
        return [
            new Index('idx_guid_id', ['guid_id' => 36]),
            new Index('idx_order_entity_most', ['contract_id', 'store_guid']),
            new Index('idx_is_active', ['is_active']),
        ];
    }

    public static function isHaveAccess($account_id, $order_id)
    {
        global $USER;
        $order = self::getRow([
            'filter' => [
                '=id' => $order_id,
                '=contract.company.Hogart\Lk\Entity\AccountCompanyRelationTable:company.account.id' => $account_id
            ]
        ]);
        return !empty($order) || $USER->IsAdmin();
    }

    public static function showName($order = [], $prefix = '')
    {
        $name = "Заказ №" . ($order[$prefix . 'number'] ? : "<sup>получение</sup>");
        $date = $order[$prefix . 'order_date'];
        if (!empty($date) && $date instanceof DateTime) {
            $name .= " от " . $date->format("d-m-Y");
        }

        return $name;
    }

    /**
     * @param $status
     * @return mixed
     */
    public static function getStatusText($status)
    {
        return [
            self::STATUS_NEW => "Новый",
            self::STATUS_IN_WORK => "В работе",
            self::STATUS_FINISHED => "Завершен"
        ][$status];
    }

    /**
     * @param $state
     * @return mixed
     */
    public static function getStateText($state)
    {
        return [
            self::STATE_NORMAL => "В работе",
            self::STATE_DRAFT => "Черновик",
            self::STATE_ARCHIVE => "Архив"
        ][$state];
    }

    /**
     * @param $type
     * @return mixed
     */
    public static function getTypeText($type)
    {
        return [
            self::TYPE_SALE => "Товары",
            self::TYPE_PROMO => "Реклама"
        ][$type];
    }

    public static function getAccountsByOrder($order_id)
    {
        return self::getList([
            'filter' => [
                '=id' => $order_id
            ],
            'select' => [
                'a_' => 'contract.company.Hogart\Lk\Entity\AccountCompanyRelationTable:company.account'
            ]
        ])->fetchAll();
    }

    public static function getCompaniesByAccount($account_id, $state = self::STATE_NORMAL, $filter = [])
    {
        $filter = array_merge([
            '=state' => $state,
            '=contract.company.Hogart\Lk\Entity\AccountCompanyRelationTable:company.account.id' => $account_id
        ], $filter);
        $companies = self::getList([
            'filter' => $filter,
            'select' => [
                'co_' => 'contract.company',
            ],
            'group' => [
                'contract.company_id'
            ]
        ])->fetchAll();
        return $companies;
    }
    public static function getStoresByAccount($account_id, $state = self::STATE_NORMAL, $filter = [])
    {
        $filter = array_merge([
            '=state' => $state,
            '=contract.company.Hogart\Lk\Entity\AccountCompanyRelationTable:company.account.id' => $account_id
        ], $filter);
        $companies = self::getList([
            'filter' => $filter,
            'select' => [
                's_' => 'store'
            ],
            'group' => [
                'store_guid'
            ]
        ])->fetchAll();
        return $companies;
    }

    public static function getOrder($order_id, $filter = [], $item_filter = [], $account_id = null)
    {
        if (is_int($order_id)) {

            $filter = array_merge([
                '=id' => $order_id,
                '=is_active' => true
            ], $filter);

            $order = self::getRow([
                'filter' => $filter,
                'select' => [
                    '*',
                    'c_' => 'contract',
                    's_' => 'store',
                    'co_' => 'contract.company',
                    'co_chief_' => 'contract.company.chief_contact',
                    'cop_' => 'contract.company.main_payment_account.payment_account',
                    'hco_' => 'contract.hogart_company',
                    'hcop_' => 'contract.hogart_company.main_payment_account.payment_account',
                ]
            ]);
        } else {
            $order = $order_id;
        }

        $account_id = $account_id ? : $order['account_id'];

        if (null !== $account_id) {
            $account = AccountTable::getRow([
                'filter' => [
                    '=id' => $account_id
                ],
                'select' => [
                    'a_' => '*',
                    'm_' => 'main_manager'
                ]
            ]);
            $order = array_merge($order, $account);
        }

        $currencies = array_reduce(\CStorage::getVar('HOGART.CURRENCIES'), function ($result, $item) {
            $result[$item['CURRENCY']] = $item;
            return $result;
        }, []);
        $order['currency'] = $currencies[$order['c_currency_code']];

        if (!empty($order)) {
            $order['rtu'] = RTUTable::getList([
                'filter' => [
                    '=order_id' => $order['id']
                ],
                "count_total" => true,
            ])->getCount();

            $order['history'] = OrderEventTable::getList([
                'filter' => [
                    '=order_id' => $order['id']
                ],
                "count_total" => true,
            ])->getCount();

            $order['items'] = OrderItemTable::getList([
                'filter' => array_merge([
                    '=order_id' => $order['id']
                ], $item_filter),
                'select' => [
                    '*',
                    '' => 'item',
                    'ELEMENT_CODE' => 'item.CODE',
                    'url' => 'item.IBLOCK.DETAIL_PAGE_URL'
                ],
                'order' => [
                    'item_group' => 'ASC',
                    'id' => 'ASC'
                ]
            ])->fetchAll();

            if (empty($order['items'])) {
                return null;
            }

            $items = array_reduce($order['items'], function ($result, $item) { $result[$item['ID']] = $item; return $result; }, []);
            \CIBlockElement::GetPropertyValuesArray($items, CATALOG_IBLOCK_ID, ['ID' => array_keys($items)], ['CODE' => ['sku', 'default_count']]);

            $products = ProductTable::getList([
                'filter' => [
                    '@ID' => array_keys($items)
                ]
            ])->fetchAll();

            $products = array_reduce($products, function ($result, $item) { $result[$item['ID']] = $item; return $result; }, []);

            $measures = [];
            foreach ($order['items'] as &$item) {
                $order['totals']['items'] += $item['total'] + (!$order['c_vat_include'] ? $item['total_vat'] : 0);
                $order['totals']['vat'] += $item['total_vat'];
                $order['totals']['discount_price'] += ($item['discount_price'] * $item['count']);
                $order['totals']['price'] += ($item['price'] * $item['count']);

                $order['shipment_flag'] |= (1<<$item['status']);

                $item['url'] = \CIBlock::ReplaceDetailUrl($item['url'], $item, false, 'E');
                $item['props'] = $items[$item['item_id']];
                $item['product'] = $products[$item['item_id']];
                $measures[] = $item['product']['MEASURE'];
                $order['totals']['group'][$item['item_group']] += ($item['total'] + (!$order['c_vat_include'] ? $item['total_vat'] : 0));
                $order['totals']['volume'][$item['item_group']] += (round($item['product']['WIDTH'] * $item['product']['LENGTH'] * $item['product']['HEIGHT'] / pow(1000, 3) * $item['count'], 2));
                $order['totals']['weight'][$item['item_group']] += round($item['product']['WEIGHT'] * $item['count'], 2);
            }

            $order['items'] = array_reduce($order['items'], function ($result, $item) {
                $result[$item['item_group']][] = $item;
                return $result;
            }, []);

            $measuresRes = \CCatalogMeasure::getList(
                array(),
                array('@ID' => array_unique($measures)),
                false,
                false,
                array('ID', 'SYMBOL_RUS')
            );
            while ($measure = $measuresRes->GetNext()) {
                $order['measures'][$measure['ID']] = $measure['SYMBOL_RUS'];
            }

            $order['payments'] = OrderPaymentTable::getList([
                'filter' => [
                    '=order_id' => $order['id'],
                    '=is_active' => true
                ],
                'order' => [
                    'payment_date' => 'ASC'
                ]
            ])->fetchAll();

            foreach ($order['payments'] as $payment) {
                $order['totals']['payments'] += $payment['total'];
            }
            $order['totals']['release'] = max(0, $order['totals']['items'] - $order['totals']['payments']);
        }

        return $order;
    }

    public function getByAccountCount($account_id, $state = self::STATE_NORMAL)
    {
        $filter = [
            '=state' => $state,
            '=contract.company.Hogart\Lk\Entity\AccountCompanyRelationTable:company.account.id' => $account_id
        ];
        $ordersResult = self::getList([
            'filter' => $filter,
            'select' => [
                '*',
                'a_' => 'account',
                'c_' => 'contract',
                's_' => 'store',
                'co_' => 'contract.company',
                'hco_' => 'contract.hogart_company'
            ],
            'order' => [
                'order_date' => 'DESC'
            ],
            "count_total" => true,
        ]);
        return $ordersResult->getCount();
    }

    public static function getByAccount($account_id, PageNavigation $nav = null, $state = self::STATE_NORMAL, $filter = [], $item_filter = [])
    {
        $filter = array_merge([
            '=is_active' => true,
            '=state' => $state,
            '=contract.is_active' => true,
            '=contract.company.is_active' => true,
            '=contract.company.Hogart\Lk\Entity\AccountCompanyRelationTable:company.account.id' => $account_id
        ], $filter);
        $ordersResult = self::getList([
            'filter' => $filter,
            'select' => [
                '*',
                'c_' => 'contract',
                's_' => 'store',
                'co_' => 'contract.company',
                'co_chief_' => 'contract.company.chief_contact',
                'cop_' => 'contract.company.main_payment_account.payment_account',
                'hco_' => 'contract.hogart_company',
                'hcop_' => 'contract.hogart_company.main_payment_account.payment_account',
            ],
            'order' => [
                'order_date' => 'DESC'
            ],
            "count_total" => true,
            "offset" => null !== $nav ? $nav->getOffset() : null,
            "limit" => null !== $nav ? $nav->getLimit() : null,
        ]);

        if (null !== $nav) {
            $nav->setRecordCount($ordersResult->getCount());
        }
        $orders = $ordersResult->fetchAll();

        foreach ($orders as &$order) {
            $order = self::getOrder($order, $filter, $item_filter, $account_id);
        }
        return $orders;
    }

    public static function getShipmentOrders($account_id, $store)
    {
        $orders = self::getByAccount($account_id, null, self::STATE_NORMAL, [
            '=store_guid' => $store
        ], [
            '=status' => OrderItemTable::STATUS_IN_RESERVE,
        ]);
        $type_id = AddressTypeTable::getByField('code', AddressTypeTable::TYPE_DELIVERY)['id'];
        return array_reduce($orders, function ($result, $order) use($type_id) {
            if(!empty($order)) {
                if (!$order['sale_granted'] && $order['totals']['release'] > 0) return $result;
                $order['addresses'] = array_reduce(AddressTable::getByOwner($order['co_id'], AddressTable::OWNER_TYPE_CLIENT_COMPANY, [
                    '=type_id' => $type_id
                ])[$type_id], function ($result, $address) {
                    $result[$address['guid_id']] = $address;
                    return $result;
                });
                $order['contacts'] = array_reduce(ContactRelationTable::getContactsByOwner($order['co_id'], ContactRelationTable::OWNER_TYPE_CLIENT_COMPANY), function ($result, $contact) {
                    $result[(string)$contact['id']] = $contact;
                    return $result;
                }, []);
                $result[$order['store_guid']][] = $order;
            }
            return $result;
        }, []);
    }

    public static function isProvideShipmentFlag($flag, $test)
    {
        return (bool)($flag & (1<<$test));
    }

    public static function getShipmentByFlag($flag)
    {
        $text =<<<HTML
<span class="color-warning">Заказ не отгружался</span> 
HTML;
        if (($flag & (1<<OrderItemTable::STATUS_SHIPMENT)) > 0) {
            $text =<<<HTML
<span class="color-primary">Отгружен частично</span>
HTML;
        }

        if (($flag & (1<<OrderItemTable::STATUS_SHIPMENT_PROCESS)) > 0) {
            $text =<<<HTML
<span class="color-primary">В процессе отгрузки</span>
HTML;
        }

        if (($flag ^ (1<<OrderItemTable::STATUS_IN_RESERVE)) == 0) {
            $text .=<<<HTML
<br><span class="color-primary">часть товаров доступна для отгрузки</span>
HTML;
        }

        if ($flag == (1<<OrderItemTable::STATUS_IN_RESERVE)) {
            $text =<<<HTML
<span class="color-primary">Заказ полностью готов к отгрузке</span>
HTML;
        }

        if (($flag ^ (1<<OrderItemTable::STATUS_SHIPMENT)) == 0) {

            $text =<<<HTML
<span class="color-primary">Отгружен полностью</span> 
HTML;
        }

        return $text;
    }

    public static function copyToDraft($order_id, $account_id = null)
    {
        $order = self::getById($order_id)->fetch();
        unset($order['id']);
        unset($order['guid_id']);
        $order['state'] = self::STATE_DRAFT;
        $order['order_date'] = new DateTime();
        if (null !== $account_id) {
            $order['account_id'] = $account_id;
        }
        $result = self::add($order);

        if ($result->getId()) {
            $new_order_id = $result->getId();
            $items = OrderItemTable::getList([
                'filter' => [
                    '=order_id' => $order_id
                ]
            ])->fetchAll();
            foreach ($items as $item) {
                $item['order_id'] = $new_order_id;
                $item['status'] = OrderItemTable::STATUS_NOT_PROVIDED;
                unset($item['id']);
                OrderItemTable::add($item);
            }
            new FlashSuccess(vsprintf("%s скопирован в черновики", [self::showName($order)]));
        }
    }

    public static function copyToCart($order_id, $account_id = null)
    {
        $order = self::getById($order_id)->fetch();
        $account_id = null !== $account_id ? $account_id : $order['account_id'];
        $carts = CartTable::getAccountCarts($account_id);
        foreach ($carts as $cart) {
            CartTable::delete($cart['guid_id']);
        }

        $items = OrderItemTable::getList([
            'filter' => [
                '=order_id' => $order_id,
                '=item.ACTIVE' => 'Y'
            ]
        ])->fetchAll();
        foreach ($items as $item) {
            CartItemTable::addItem(
                $account_id,
                $item['item_id'],
                $item['count'],
                $item['item_group'],
                $order['contract_id'],
                $order['store_guid']
            );
        }
        new FlashSuccess(vsprintf("%s скопирован в корзину", [self::showName($order)]));
    }

    /**
     * @param string $cart_id
     * @param $perm_reserve
     * @param $note
     * @return bool|int
     * @throws \Exception
     */
    public static function createByCart($cart_id, $perm_reserve, $note)
    {
        global $DB;
        $DB->StartTransaction();

        $cart = CartTable::getByPrimary($cart_id)->fetch();
        if (empty($cart['contract_id'])) {
            new FlashError("Не указан договор для создания заказа");
            return false;
        }
        $account = AccountTable::getAccountById($cart['account_id']);
        $cart = CartTable::getAccountCartList($cart['account_id'], $cart_id);
        $result = self::add([
            'contract_id' => $cart['contract_id'],
            'store_guid' => $cart['store_guid'],
            'account_id' => $cart['account_id'],
            'staff_id' => $account['main_manager_id'],
            'order_date' => ($date = new DateTime()),
            'type' => ($cart['item_type'] == CartTable::ITEM_TYPE_GOODS ? self::TYPE_SALE : self::TYPE_PROMO),
            'perm_reserve' => boolval($perm_reserve),
            'note' => $note,
            'is_active' => true
        ]);

        if (!$result->getId()) {
            new FlashError("Не удалось создать заказ");
            $DB->Rollback();
            return false;
        }

        $order_id = $result->getId();
        $k = 1;
        foreach ($cart['items'] as $item_group => $items) {
            foreach ($items as $item) {
                $new_item = [
                    'order_id' => $order_id,
                    'string_number' => $k++,
                    'item_id' => $item['item_id'],
                    'item_group' => $item_group,
                    'count' => $item['count'],
                    'price' => $item['price'],
                    'discount' => floatval($item['discount']['discount']),
                    'discount_price' => floatval($item['discount']['price']),
                    'total' => ($item['discount']['price'] ? : $item['price']) * $item['count']
                ];
                if ($cart['c_vat_rate'] != ContractTable::VAT_18) {
                    $koeff = 1.18 * (1 + $cart['c_vat_rate'] / 100);

                    $new_item['price'] = round($new_item['price'] / $koeff, 2);
                    $new_item['discount_price'] = round($new_item['discount_price'] / $koeff, 2);
                    $new_item['total'] = ($new_item['discount_price'] ? : $new_item['price']) * $new_item['count'];
                }
                $new_item['total_vat'] = round($new_item['total'] * ($cart['c_vat_rate'] / 100) / (1 + $cart['c_vat_rate'] / 100), 2);

                if (!$cart['c_vat_include']) {
                    $koeff = (1 + $cart['c_vat_rate'] / 100);

                    $new_item['price'] = round($new_item['price'] / $koeff, 2);
                    $new_item['discount_price'] = round($new_item['discount_price'] / $koeff, 2);
                    $new_item['total'] = ($new_item['discount_price'] ? : $new_item['price']) * $new_item['count'];
                    $new_item['total_vat'] = round($new_item['total'] * $cart['c_vat_rate'] / 100, 2);
                }
                try {
                    OrderItemTable::add($new_item);
                } catch (\Exception $e) {
                    new FlashError("Не удалось создать заказ");
                    $DB->Rollback();
                    return false;
                }
            }
        }

        $DB->Commit();
        self::publishToRabbit(new OrderExchange(), new Order([self::getOrder($order_id)]));
        if ($order_id) {
            new FlashSuccess("Создан новый " . OrderTable::showName(OrderTable::getRowById($order_id)) . " <b>(перейти к заказу)</b>", '/account/order/' . $order_id, 0);
        }
        return $order_id;
    }

    public static function resort($order_id)
    {
        $items = OrderItemTable::getList([
            'filter' => [
                '=order_id' => $order_id
            ],
            'order' => [
                'string_number' => 'ASC',
                'id' => 'ASC'
            ]
        ])->fetchAll();
        foreach ($items as $k => $item) {
            OrderItemTable::update($item['id'], [
                'string_number' => intval(++$k)
            ]);
        }
    }

    public static function onBeforeAdd(Event $event)
    {
        $fields = $event->getParameter('fields');
        $result = new EventResult();
        $result->modifyFields([
            'type' => intval($fields['type']),
            'status' => intval($fields['status']),
            'is_active' => boolval($fields['is_active']),
            'perm_reserve' => boolval($fields['perm_reserve'])
        ]);
        return $result;
    }

    public static function onBeforeUpdate(Event $event)
    {
        $fields = $event->getParameter('fields');
        $result = new EventResult();

        if ($fields['status'] == self::STATUS_FINISHED) {
            $result->modifyFields([
                'state' => self::STATE_ARCHIVE
            ]);
        }
        return $result;
    }

    public static function onAfterDelete(Event $event)
    {
        $id = $event->getParameter('id')['id'];
        new FlashSuccess(vsprintf("Заказ %s удален", [OrderTable::showName($id)]));
    }


    public static function onBeforeDelete(Event $event)
    {
        $id = $event->getParameter('id')['id'];
        OrderItemTable::deleteByOrderId($id);
    }
}