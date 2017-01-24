<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 23/11/2016
 * Time: 14:35
 */

namespace Hogart\Lk\Entity;


use Bitrix\Catalog\ProductTable;
use Bitrix\Main\Entity\BooleanField;
use Bitrix\Main\Entity\Event;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Entity\TextField;
use Hogart\Lk\Exchange\RabbitMQ\Exchange\OrderExchange;
use Hogart\Lk\Exchange\SOAP\Request\Order;
use Hogart\Lk\Helper\Template\FlashError;

class OrderEditTable extends AbstractEntity
{
    /**
     * @inheritDoc
     */
    public static function getTableName()
    {
        return "h_order_edit";
    }

    /**
     * @inheritDoc
     */
    public static function getMap()
    {
        return [
            new IntegerField("order_id", [
                'primary' => true,
            ]),
            new ReferenceField("order", __NAMESPACE__ . "\\OrderTable", ["=this.order_id" => "ref.id"]),
            new TextField("note"),
            new BooleanField("is_changed", [
                'default_value' => false
            ])
        ];
    }

    public static function applyChangesToOrder($order_id)
    {
        global $DB;
        $order = self::getRowById($order_id);
        $DB->StartTransaction();
        OrderTable::update($order_id, [
            'note' => $order['note']
        ]);

        $items = OrderItemEditTable::getList([
            'filter' => [
                '=order_id' => $order_id
            ],
        ])->fetchAll();

        $k = 1;
        foreach ($items as $item) {
            $rel_items = OrderItemEditRelTable::getList([
                'filter' => [
                    '=order_edit_item_guid' => $item['guid_id'],
                ],
                'select' => [
                    'oi_' => 'order_item'
                ],
                'order' => [
                    'order_item.status' => 'ASC'
                ]
            ])->fetchAll();

            if ($item['is_new']) {
                // Строка в редактировании НОВАЯ (менялась скидка или цена)
                // => значит нужно всегда добавлять новую строку,
                // но при этом в ноль скидывать строки в заказе (если только нет необеспеченных строк)
                $rel_item_for_change = null;
                foreach ($rel_items as $rel_item) {
                    if ($rel_item['oi_status'] == OrderItemTable::STATUS_NOT_PROVIDED) {
                        $rel_item_for_change = $rel_item;
                    } else {
                        OrderItemTable::update($rel_item['oi_id'], [
                            'count' => 0,
                            'total' => 0,
                            'total_vat' => 0,
                        ]);
                    }
                }

                $new_item = [
                    'order_id' => $order_id,
                    'string_number' => $k++,
                    'item_id' => $item['item_id'],
                    'item_group' => $item['item_group'],
                    'count' => $item['count'],
                    'price' => $item['price'],
                    'discount' => floatval($item['discount']),
                    'discount_price' => floatval($item['discount_price']),
                    'total' => $item['total'],
                    'total_vat' => $item['total_vat']
                ];

                try {
                    if (null !== $rel_item_for_change) {
                        OrderItemTable::update($rel_item_for_change['oi_id'], $new_item);
                    } else {
                        OrderItemTable::add($new_item);
                    }
                } catch (\Exception $e) {
                    new FlashError("Не удалось изменить заказ");
                    $DB->Rollback();
                    return false;
                }

            } else {
                // Строка в редактировании не новая (не менялась скидка и цена)
                // => значит менялось только кол-во
                // => можно менять только суммы по строкам с кол-вом
                $rel_count = 0;
                foreach ($rel_items as $rel_item) {
                    $rel_count += $rel_item['oi_count'];
                }

                if ($item['count'] < $rel_count) {
                    $reduce_count = $rel_count - $item['count'];
                    foreach ($rel_items as $rel_item) {
                        $new_count = max(0, $rel_item['oi_count'] - $reduce_count);
                        OrderItemTable::update($rel_item['oi_id'], [
                            'count' => $new_count,
                            'total' => $item['total'] / $item['count'] * $new_count,
                            'total_vat' => $item['total_vat'] / $item['count'] * $new_count,
                        ]);
                        $reduce_count -= ($rel_item['oi_count'] - $new_count);
                        if ($reduce_count <= 0) break;
                    }
                } elseif ($item['count'] > $rel_count) {
                    $increase_count = $item['count'] - $rel_count;
                    // ищем позицию со статусом "Не обеспечен"
                    $rel_item_for_increase = null;
                    foreach ($rel_items as $rel_item) {
                        if ($rel_item['oi_status'] == OrderItemTable::STATUS_NOT_PROVIDED) {
                            $rel_item_for_increase = $rel_item;
                            break;
                        }
                    }

                    if (null === $rel_item_for_increase) {
                        // добавляем новую позицию в заказ, т.к. нет позиций в необеспеченном статусе
                        $new_item = [
                            'order_id' => $order_id,
                            'string_number' => $k++,
                            'item_id' => $item['item_id'],
                            'item_group' => $item['item_group'],
                            'count' => $increase_count,
                            'price' => $item['price'],
                            'discount' => floatval($item['discount']),
                            'discount_price' => floatval($item['discount_price']),
                            'total' => $item['total'] / $item['count'] * $increase_count,
                            'total_vat' => $item['total_vat'] / $item['count'] * $increase_count
                        ];
                        try {
                            OrderItemTable::add($new_item);
                        } catch (\Exception $e) {
                            new FlashError("Не удалось изменить заказ");
                            $DB->Rollback();
                            return false;
                        }
                    } else {
                        $new_count = $increase_count + $rel_item_for_increase['oi_count'];
                        OrderItemTable::update($rel_item_for_increase['oi_id'], [
                            'count' => $new_count,
                            'total' => $item['total'] / $item['count'] * $new_count,
                            'total_vat' => $item['total_vat'] / $item['count'] * $new_count,
                        ]);
                    }
                }
            }
        }

        self::delete($order_id);
        $DB->Commit();

        OrderTable::publishToRabbit(new OrderExchange(), new Order([OrderTable::getOrder($order_id)]));

        return true;
    }

    public static function changeNote($order_id, $note)
    {
        $order = OrderTable::getRow([
            'filter' => [
                '=id' => $order_id
            ],
            'select' => [
                'note'
            ]
        ]);

        OrderEditTable::update($order_id, [
            'note' => (string)$note,
            'is_changed' => $order['note'] != (string)$note
        ]);

        return true;
    }

    public static function copyFromOrder($order_id)
    {
        global $DB;

        $order = OrderTable::getOrder($order_id, [
            '=is_actual' => true
        ], [
            '<status' => OrderItemTable::STATUS_SHIPMENT_PROCESS
        ]);
        if (empty($order)) {
            new FlashError("Заказ не найден!");
            return false;
        }

        $DB->StartTransaction();

        self::createOrUpdateByField([
            'order_id' => $order['id'],
            'note' => $order['note']
        ], 'order_id');

        foreach ($order['items'] as $item_group => $items) {
            foreach ($items as $item) {

                $guid_id = OrderItemEditTable::getUUID(
                    $order_id,
                    $item['item_id'],
                    $item_group,
                    $item['price'],
                    $item['discount']
                );

                $row = OrderItemEditTable::getRowById($guid_id);
                if (!empty($row)) {
                    $new_count = $item['count'] + $row['count'];
                    $item['discount_price'] = ($item['discount_price'] / $item['count']) * $new_count;
                    $item['total'] = ($item['total'] / $item['count']) * $new_count;
                    $item['total_vat'] = ($item['total_vat'] / $item['count']) * $new_count;
                    $item['count'] = $new_count;
                }

                $item_result = OrderItemEditTable::createOrUpdateByField([
                    'order_id' => $order['id'],
                    'item_id' => $item['item_id'],
                    'count' => $item['count'],
                    'price' => $item['price'],
                    'discount' => $item['discount'],
                    'discount_price' => $item['discount_price'],
                    'total' => $item['total'],
                    'total_vat' => $item['total_vat'],
                    'item_group' => $item_group,
                    'is_new' => false
                ], 'guid_id');

                OrderItemEditRelTable::replace([
                    'order_edit_item_guid' => $item_result->getId(),
                    'order_item_id' => $item['id']
                ]);
            }
        }

        $DB->Commit();

        return self::getOrder($order_id);
    }

    /**
     * @param $item
     * @param $contract_id
     * @param $vat_rate
     * @param $vat_include
     * @return array
     */
    public static function prepareItemPrices($item, $contract_id, $vat_rate, $vat_include)
    {
        $prices = [];
        $discounts = [];
        if (empty($item['price'])) {
            $group_id = \CCatalogGroup::GetList([], ['BASE' => 'Y'])->Fetch()['ID'];
            $price = GetCatalogProductPrice($item['item_id'], $group_id);
            $item['price'] = $price['PRICE'];
        }
        $prices[$item['item_id']] = $item['price'];

        if (!empty($item['discount'])) {
            $discounts[$item['item_id']] = floatval($item['discount']);
        }

        $prices = CompanyDiscountTable::preparePricesByContract($contract_id, $prices, $discounts);

        $new_item = [
            'price' => floatval($item['price']),
            'discount' => floatval($prices[$item['item_id']]['discount']),
            'discount_price' => $prices[$item['item_id']]['price'],
            'total' => $prices[$item['item_id']]['price'] * $item['count'],
        ];

        if ($vat_rate != ContractTable::VAT_18) {
            $koeff = 1.18 * (1 + $vat_rate / 100);

            $new_item['price'] = round($new_item['price'] / $koeff, 2);
            $new_item['discount_price'] = round($new_item['discount_price'] / $koeff, 2);
            $new_item['total'] = ($new_item['discount_price'] ? : $new_item['price']) * $new_item['count'];
        }
        $new_item['total_vat'] = round($new_item['total'] * ($vat_rate / 100) / (1 + $vat_rate / 100), 2);

        if (!$vat_include) {
            $koeff = (1 + $vat_rate / 100);

            $new_item['price'] = round($new_item['price'] / $koeff, 2);
            $new_item['discount_price'] = round($new_item['discount_price'] / $koeff, 2);
            $new_item['total'] = ($new_item['discount_price'] ? : $new_item['price']) * $new_item['count'];
            $new_item['total_vat'] = round($new_item['total'] * $vat_rate / 100, 2);
        }

        return array_merge($item, $new_item);
    }

    /**
     * @param $item
     * @param $contract_id
     * @param $vat_rate
     * @param $vat_include
     * @return array
     */
    protected static function updateItemPrices($item, $contract_id, $vat_rate, $vat_include)
    {
        $group_id = \CCatalogGroup::GetList([], ['BASE' => 'Y'])->Fetch()['ID'];
        $price = GetCatalogProductPrice($item['item_id'], $group_id);
        if ($item['price'] == $price['PRICE']) return $item;

        $item['price'] = $price['PRICE'];
        $item = self::prepareItemPrices($item, $contract_id, $vat_rate, $vat_include);
        $fields = array_keys(OrderItemEditTable::getEntity()->getScalarFields());
        $new_item = array_reduce($fields, function ($result, $field) use ($item) {
            $result[$field] = $item[$field];
            return $result;
        }, []);

        OrderItemEditTable::update($item['guid_id'], $new_item);

        return $item;
    }

    public static function getOrder($order_id)
    {
        $current_order = OrderTable::getOrder($order_id, [], [
            '>=status' => OrderItemTable::STATUS_SHIPMENT_PROCESS
        ]);

        $order = self::getRow([
            'filter' => [
                '=order_id' => $order_id,
                '=order.is_actual' => true
            ],
            'select' => [
                '_' => 'order',
                'note' => 'note',
                'is_changed' => 'is_changed',
                'c_' => 'order.contract',
                's_' => 'order.store',
                'co_' => 'order.contract.company',
                'co_chief_' => 'order.contract.company.chief_contact',
                'cop_' => 'order.contract.company.main_payment_account.payment_account',
                'hco_' => 'order.contract.hogart_company',
                'hcop_' => 'order.contract.hogart_company.main_payment_account.payment_account'
            ]
        ]);

        if (empty($order)) {
            return null;
        }

        $order['items'] = OrderItemEditTable::getList([
            'filter' => [
                '=order_id' => $order['_id'],
            ],
            'select' => [
                '*',
                '' => 'item',
                'ELEMENT_CODE' => 'item.CODE',
                'url' => 'item.IBLOCK.DETAIL_PAGE_URL',
            ],
            'order' => [
                'item_group' => 'ASC',
                'created_at' => 'ASC'
            ]
        ])->fetchAll();

        foreach ($current_order['items'] as $item_group => $items) {
            foreach ($items as $item) {
                $item['not_editable'] = true;
                $order['items'][] = $item;
            }
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
        $prices = [];
        $discounts = [];
        foreach ($order['items'] as &$item) {

            if ($item['is_new']) {
                $item = self::updateItemPrices($item, $order['c_id'], $order['c_vat_rate'], $order['c_vat_include']);
            }

            $order['totals']['items'] += ($item['total'] + (!$order['c_vat_include'] ? $item['total_vat'] : 0));
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
            $prices[$item['item_id']] = $item['price'];
            $discounts[$item['item_id']] = $item['discount'];
        }

        $prices = CompanyDiscountTable::preparePricesByContract($order['c_id'], $prices, $discounts);

        $order['items'] = array_reduce($order['items'], function ($result, $item) use ($prices) {
            $item['max_discount'] = $prices[$item['item_id']]['max_discount'];
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

        return $order;
    }

    public static function onAfterDelete(Event $event)
    {
        $rel_items = OrderItemEditTable::getList([
            'filter' => [
                '=order_id' => $event->getParameter('id')['order_id']
            ]
        ])->fetchAll();
        foreach ($rel_items as $rel_item) {
            OrderItemEditTable::delete($rel_item['guid_id']);
        }
    }
}
