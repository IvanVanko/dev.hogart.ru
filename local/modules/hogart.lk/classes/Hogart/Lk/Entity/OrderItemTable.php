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

class OrderItemTable extends AbstractEntity
{
    const STATUS_NOT_PROVIDED = 0;
    const STATUS_SUPPLIER_ORDER = 1;
    const STATUS_INTERMEDIATE_STORE = 2;
    const STATUS_IN_RESERVE = 3;
    const STATUS_SHIPMENT_PROCESS = 4;
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
            new IntegerField("order_id"),
            new ReferenceField("order", "OrderTable", ["=this.order_id" => "ref.id"]),
            new IntegerField("string_number"),
            new IntegerField("item_id"),
            new ReferenceField("item", "Bitrix\\Iblock\\ElementTable", ["=this.item_id" => "ref.ID", "=ref.IBLOCK_ID" => new SqlExpression('?i', CATALOG_IBLOCK_ID)]),
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
            new DateField("delivery_time"),
            new StringField("group")
        ];
    }

}