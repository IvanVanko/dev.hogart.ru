<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 23/11/2016
 * Time: 14:52
 */

namespace Hogart\Lk\Entity;


use Bitrix\Iblock\ElementTable;
use Bitrix\Main\DB\SqlExpression;
use Bitrix\Main\Entity\BooleanField;
use Bitrix\Main\Entity\DatetimeField;
use Bitrix\Main\Entity\Event;
use Bitrix\Main\Entity\EventResult;
use Bitrix\Main\Entity\FloatField;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Entity\Result;
use Bitrix\Main\Entity\ScalarField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Type\DateTime;
use Hogart\Lk\Field\GuidField;
use Hogart\Lk\Helper\Template\Error;
use Hogart\Lk\Helper\Template\FlashError;
use Hogart\Lk\Helper\Template\FlashWarning;
use Ramsey\Uuid\Uuid;

class OrderItemEditTable extends AbstractEntity
{
    /**
     * @inheritDoc
     */
    public static function getTableName()
    {
        return "h_order_item_edit";
    }

    /**
     * @inheritDoc
     */
    public static function getMap()
    {
        return [
            new GuidField("guid_id", ["primary" => true]),
            new IntegerField("order_id"),
            new ReferenceField("order", __NAMESPACE__ . "\\OrderEditTable", ["=this.order_id" => "ref.order_id"]),
            new IntegerField("item_id"),
            new ReferenceField("item", "Bitrix\\Iblock\\ElementTable", ["=this.item_id" => "ref.ID", "=ref.IBLOCK_ID" => new SqlExpression('?i', CATALOG_IBLOCK_ID)]),
            new IntegerField("count", [
                "default_value" => 0
            ]),
            new FloatField("price"),
            new FloatField("discount"),
            new FloatField("discount_price"),
            new FloatField("total"),
            new FloatField("total_vat"),
            new StringField("item_group", [
                "default_value" => ""
            ]),
            new DatetimeField("created_at"),
            new BooleanField('is_new', [
                "default_value" => true
            ])
        ];
    }

    public static function getUUID($order_id, $item_id, $item_group, $price, $discount)
    {
        return Uuid::uuid5(Uuid::NAMESPACE_OID, implode('|', [
            $order_id,
            $item_id,
            $item_group,
            $price,
            $discount,
        ]))->toString();
    }

    public static function getDefaultCount($item_id, $count)
    {
        $props = array_reduce([$element = ElementTable::getById($item_id)->fetch()], function ($result, $item) { $result[$item['ID']] = $item; return $result; }, []);
        \CIBlockElement::GetPropertyValuesArray($props, CATALOG_IBLOCK_ID, ['ID' => array_keys($props)], ['CODE' => ['default_count', 'sku']]);
        $default_count = intval($props[$item_id]['default_count']['VALUE']) ? : 1;
        $count = ceil($count / $default_count) * $default_count;

        return [$count, $default_count];
    }

    public static function softDelete($order_id, $guid_id)
    {
        global $DB;
        if (!is_array($guid_id)) {
            $guid_id = [$guid_id];
        }
        $DB->StartTransaction();
        foreach ($guid_id as $id) {
            $r = self::update($id, [
                'count' => 0,
                'total' => 0,
                'total_vat' => 0,
            ]);

            if (!$r->getId()) {
                $DB->Rollback();
                return false;
            }
        }

        OrderEditTable::update($order_id, [
            'is_changed' => true
        ]);

        $DB->Commit();

        return true;
    }

    public static function setMaxDiscounts($order_id)
    {
        $order = OrderTable::getRow([
            'filter' => [
                '=id' => $order_id
            ],
            'select' => [
                'c_' => 'contract',
            ]
        ]);

        $items = self::getList([
            'filter' => [
                '=order_id' => $order_id,
            ]
        ])->fetchAll();

        foreach ($items as $item) {
            $max_discount = CompanyDiscountTable::getDiscountByContractAndItem($order['c_id'], $item['item_id']);
            if ($max_discount != $item['discount']) {
                self::changeDiscount($order_id, $item['guid_id'], $item['item_group'], $max_discount);
            }
        }

        return true;
    }

    public static function changeDiscount($order_id, $item_guid_id, $item_group, $discount)
    {
        global $DB;

        $row = self::getRowById($item_guid_id);

        $order = OrderTable::getRow([
            'filter' => [
                '=id' => $order_id
            ],
            'select' => [
                'c_' => 'contract',
            ]
        ]);

        $max_discount = CompanyDiscountTable::getDiscountByContractAndItem($order['c_id'], $row['item_id']);
        if ($max_discount < $discount) {
            new FlashError(vsprintf("Скидка не может превышать <b>%.2f%%</b>", floatval($max_discount)));
            return false;
        }

        $new_item = OrderEditTable::prepareItemPrices([
            'item_id' => $row['item_id'],
            'discount' => $discount,
            'count' => $row['count']
        ], $order['c_id'], $order['c_vat_rate'], $order['c_vat_include']);

        $guid_id = self::getUUID(
            $order_id,
            $row['item_id'],
            $item_group,
            $new_item['price'],
            $new_item['discount']
        );

        $ext_row = self::getRowById($guid_id);
        if (!empty($ext_row)) {
            $new_item = OrderEditTable::prepareItemPrices([
                'item_id' => $ext_row['item_id'],
                'count' => $row['count'] + $ext_row['count']
            ], $order['c_id'], $order['c_vat_rate'], $order['c_vat_include']);
        }

        $DB->StartTransaction();

        $result = self::createOrUpdateByField(array_merge([
            'order_id' => $order_id,
            'item_group' => $item_group,
            'created_at' => $row['created_at'],
        ], $new_item), 'guid_id');

        if ($result->getId()) {
            $rel_items = OrderItemEditRelTable::getList([
                'filter' => [
                    '=order_edit_item_guid' => $row['guid_id']
                ]
            ])->fetchAll();

            $r = self::delete($row['guid_id']);
            if ($r->getErrorMessages()) {
                $DB->Rollback();
                return false;
            }

            foreach ($rel_items as $rel_item) {
                OrderItemEditRelTable::replace([
                    'order_edit_item_guid' => $result->getId(),
                    'order_item_id' => $rel_item['order_item_id']
                ]);
            }

            OrderEditTable::update($order_id, [
                'is_changed' => true
            ]);

            $DB->Commit();
            return $result;
        } else {
            $DB->Rollback();
        }
    }

    public static function changeQuantity($order_id, $item_guid_id, $item_group, $count)
    {
        $row = self::getRowById($item_guid_id);

        $order = OrderTable::getRow([
            'filter' => [
                '=id' => $order_id
            ],
            'select' => [
                'c_' => 'contract',
            ]
        ]);

        // new price check
        $group_id = \CCatalogGroup::GetList([], ['BASE' => 'Y'])->Fetch()['ID'];
        $new_price = GetCatalogProductPrice($row['item_id'], $group_id);

        if ($new_price['PRICE'] != $row['price'] && $count > $row['count']) {
            $count -= $row['count'];
        }

        list($new_count, $default_count) = self::getDefaultCount($row['item_id'], $count);
        if ($new_count != $count) {
            new FlashWarning(vsprintf("Количество товара должно быть кратно <b>%s</b>", $default_count));
        }

        $new_item = OrderEditTable::prepareItemPrices([
            'item_id' => $row['item_id'],
            'discount' => floatval($row['discount']),
            'count' => $new_count
        ], $order['c_id'], $order['c_vat_rate'], $order['c_vat_include']);

        $result = self::createOrUpdateByField(array_merge([
            'order_id' => $order_id,
            'item_group' => $item_group,
            'created_at' => $row['created_at'],
        ], $new_item), 'guid_id');

        if ($result->getId()) {
            OrderEditTable::update($order_id, [
                'is_changed' => true
            ]);
            return $result;
        }
    }

    public static function addNewItem($order_id, $item_guid_id, $item_group, $count = 1, $sku = null)
    {
        if (!defined('CATALOG_IBLOCK_ID')) {
            new Error("Не определено значение CATALOG_IBLOCK_ID");
            return false;
        }
        $item = \CIBlockElement::GetList([], ['IBLOCK_ID' => CATALOG_IBLOCK_ID, 'XML_ID' => $item_guid_id])->Fetch();
        if (empty($item) && !empty($sku)) {
            new Error(vsprintf("Товар с артикулом <b>%s</b> не найден", [$sku]));
            return false;
        }

        list($count, $default_count) = self::getDefaultCount($item['ID'], $count);

        $order = OrderTable::getRow([
            'filter' => [
                '=id' => $order_id
            ],
            'select' => [
                'c_' => 'contract',
            ]
        ]);

        $new_item = OrderEditTable::prepareItemPrices([
            'item_id' => $item['ID'],
            'count' => $count
        ], $order['c_id'], $order['c_vat_rate'], $order['c_vat_include']);

        $guid_id = self::getUUID(
            $order_id,
            $item['ID'],
            $item_group,
            $new_item['price'],
            $new_item['discount']
        );

        $row = self::getRowById($guid_id);
        if (!empty($row)) {
            $new_item = OrderEditTable::prepareItemPrices([
                'item_id' => $item['ID'],
                'count' => $count + $row['count']
            ], $order['c_id'], $order['c_vat_rate'], $order['c_vat_include']);
        }

        $result = self::createOrUpdateByField(array_merge([
            'order_id' => $order_id,
            'item_group' => $item_group,
        ], $new_item), 'guid_id');

        if ($result->getId()) {
            OrderEditTable::update($order_id, [
                'is_changed' => true
            ]);
            return $item;
        }
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
            'created_at' => empty($fields['created_at']) ? new DateTime() : $fields['created_at'],
            'guid_id' => self::getUUID(
                $fields['order_id'],
                $fields['item_id'],
                $fields['item_group'],
                $fields['price'],
                $fields['discount']
            )
        ]);
        return $result;
    }

    public static function onAfterDelete(Event $event)
    {
        $rel_items = OrderItemEditRelTable::getList([
            'filter' => [
                '=order_edit_item_guid' => $event->getParameter('id')['guid_id']
            ]
        ])->fetchAll();
        foreach ($rel_items as $rel_item) {
            OrderItemEditRelTable::delete($rel_item);
        }
    }
}