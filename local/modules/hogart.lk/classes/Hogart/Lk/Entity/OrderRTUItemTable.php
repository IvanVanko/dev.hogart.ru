<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 02/10/2016
 * Time: 00:45
 */

namespace Hogart\Lk\Entity;


use Bitrix\Main\DB\SqlExpression;
use Bitrix\Main\Entity\FloatField;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Entity\StringField;
use Hogart\Lk\Field\GuidField;
use Hogart\Lk\Helper\Template\FlashError;

class OrderRTUItemTable extends AbstractEntity
{
    /**
     * Returns DB table name for entity
     *
     * @return string
     */
    public static function getTableName()
    {
        return "h_order_rtu_item";
    }

    /**
     * Returns entity map definition
     */
    public static function getMap()
    {
        return [
            new IntegerField("id", [
                'primary' => true,
                "autocomplete" => true
            ]),
            new IntegerField("order_rtu_id"),
            new IntegerField("order_id"),
            new StringField("guid_id"),
            new IntegerField("item_id"),
            new ReferenceField("item", "Bitrix\\Iblock\\ElementTable", ["=this.item_id" => "ref.ID", "=ref.IBLOCK_ID" => new SqlExpression('?i', CATALOG_IBLOCK_ID)]),
            new StringField("item_group"),
            new IntegerField("count"),
            new FloatField("price"),
            new FloatField("discount"),
            new FloatField("discount_price"),
            new FloatField("total"),
            new FloatField("total_vat"),
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected static function getIndexes()
    {
        return [
            new Index('idx_guid_id', ['guid_id' => 77]),
            new Index('idx_order_id', ['order_id']),
        ];
    }

    public static function clearItems($order_rtu_id)
    {
        $items = self::getList([
            'filter' => [
                '=order_rtu_id' => $order_rtu_id
            ]
        ])->fetchAll();
        foreach ($items as $item) {
            self::delete($item['id']);
        }
        return true;
    }

    public static function addItem($item, $item_id)
    {
        $result = self::add($item);
        if ($result->getId()) {
            $order_item = OrderItemTable::getById($item_id)->fetch();
            $item_state = [
                'status' => OrderItemTable::STATUS_SHIPMENT_PROCESS
            ];

            if ($order_item['count'] - $item['count'] > 0) {
                $item_state = array_merge($item_state, [
                    'count' => $item['count'],
                    'total' => $item['total'],
                    'total_vat' => $item['total_vat'],
                ]);
                $order_item = array_merge($order_item, [
                    'count' => $order_item['count'] - $item['count'],
                    'total' => $order_item['total'] - $item['total'],
                    'total_vat' => $order_item['total_vat'] - $item['total_vat'],
                    'status' => intval($order_item['status'])
                ]);
                unset($order_item['id']);
                OrderItemTable::add($order_item);
            }
            OrderItemTable::update($item_id, $item_state);
        }
        return $result;
    }
}
