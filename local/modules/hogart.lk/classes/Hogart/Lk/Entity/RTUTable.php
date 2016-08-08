<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 01/08/16
 * Time: 16:09
 */

namespace Hogart\Lk\Entity;

use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Entity\DateField;
use Bitrix\Main\Entity\EnumField;
use Bitrix\Main\Entity\BooleanField;
use Hogart\Lk\Field\GuidField;

class RTUTable extends AbstractEntity
{
    const DELIVERY_OUR = 1;
    const DELIVERY_SELF = 2;
    /**
     * @inheritDoc
     */
    public static function getTableName()
    {
        return "h_rtu";
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
            new GuidField("guid_id"),

            new IntegerField("order_id"),
            new ReferenceField("order", "OrderTable", ["=this.order_id" => "ref.id"]),

            new GuidField("store_guid"),
            new ReferenceField("store", "Bitrix\\Catalog\\StoreTable", ["=this.store_guid" => "ref.XML_ID"]),

            new StringField("number"),
            new DateField("rtu_date"),
            new StringField("currency_code"),
            new ReferenceField("currency", "Bitrix\\Currency\\CurrencyTable", ["=this.currency_code" => "ref.CURRENCY"]),
            new EnumField("order_type", [
                'values' => [
                    self::DELIVERY_OUR,
                    self::DELIVERY_SELF
                ]
            ]),
            new BooleanField("is_active")
        ];
    }

    /**
     * @inheritDoc
     */
    protected static function getIndexes()
    {
        return [
            new Index('idx_guid_id', ['guid_id' => 36]),
            new Index('idx_rtu_entity_most', ['order_id', 'store_guid' => 36, 'order_type']),
            new Index('idx_is_active', ['is_active']),
        ];
    }
}