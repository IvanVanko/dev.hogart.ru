<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 01/08/16
 * Time: 15:56
 */

namespace Hogart\Lk\Entity;


use Bitrix\Main\DB\SqlExpression;
use Bitrix\Main\Entity\DateField;
use Bitrix\Main\Entity\EnumField;
use Bitrix\Main\Entity\FloatField;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Entity\StringField;
use Doctrine\Common\Annotations\Annotation\Enum;
use Hogart\Lk\Field\GuidField;

/**
 * Таблица Позиции Заказа
 * @package Hogart\Lk\Entity
 */
class OrderItemTable extends AbstractEntity
{
    /** Статус - Не определен */
    const STATUS_NOT_PROVIDED = 0;
    /** Статус -  */
    const STATUS_SUPPLIER_ORDER = 1;
    /** Статус -  */
    const STATUS_INTERMEDIATE_STORE = 2;
    /** Статус -  */
    const STATUS_IN_RESERVE = 3;
    /** Статус -  */
    const STATUS_SHIPMENT_PROCESS = 4;
    /** Статус -  */
    const STATUS_SHIPMENT = 5;

    /**
     * @inheritDoc
     */
    public static function getTableName()
    {
        return "h_order_item";
    }

    /**
     * @inheritDoc
     */
    public static function getMap()
    {
        return [
            new IntegerField("id", [
                'primary' => true,
                "autocomplete" => true
            ]),
            new StringField('d_guid_id'), // составной GUID Order_guid_id+'_'+Item_guid_id
            new IntegerField("order_id"),
            new ReferenceField("order", "OrderTable", ["=this.order_id" => "ref.id"]),
            new IntegerField("string_number"),
            new IntegerField("item_id"),
            new ReferenceField("item", "Bitrix\\Iblock\\ElementTable", ["=this.item_id" => "ref.ID", "=ref.IBLOCK.ID" => new SqlExpression('?i', CATALOG_IBLOCK_ID)]),
            new StringField("acu"),
            new StringField("name"),
            new IntegerField("count"),
            new FloatField("cost"),
            new FloatField("discount"),
            new FloatField("discount_cost"),
            new FloatField("total"),
            new FloatField("total_vat"),
            new EnumField("status", [
                'values' => [
                    self::STATUS_NOT_PROVIDED,
                    self::STATUS_SUPPLIER_ORDER,
                    self::STATUS_INTERMEDIATE_STORE,
                    self::STATUS_IN_RESERVE,
                    self::STATUS_SHIPMENT_PROCESS,
                    self::STATUS_SHIPMENT,
                ]
            ]),
            new IntegerField("delivery_time"), // Ориентировочный срок поставки если 1 или 2(заказан у поставщика)
            new StringField("group")
        ];
    }

    /**
     * @inheritDoc
     */
    protected static function getIndexes()
    {
        return [
            new Index('idx_d_guid_id', ['d_guid_id' => 73]),
            new Index('idx_order_item_entity_most', ['order_id', 'item_id', 'status']),
            new Index('idx_is_active', ['is_active']),
        ];
    }

    /**
     * Удаление записей таблицы по номеру заказа
     * @param int $id
     * @return bool
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Exception
     */
    public static function deleteByOrderId($id)
    {
        $items = self::getList([
            'filter' => [
                '=order_id' => intval($id)
            ]
        ]);
        while (($orderItem = $items->fetch())) {
            self::delete($orderItem['id']);
        }
        return true;
    }
}