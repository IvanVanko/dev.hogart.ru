<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 29/07/16
 * Time: 16:38
 */

namespace Hogart\Lk\Entity;


use Bitrix\Main\DB\SqlExpression;
use Bitrix\Main\Entity\BooleanField;
use Bitrix\Main\Entity\EnumField;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Entity\StringField;
use Hogart\Lk\Field\GuidField;

/**
 * Таблица Адресса
 * @package Hogart\Lk\Entity
 */
class AddressTable extends AbstractEntityRelation
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return "h_address";
    }

    /**
     * {@inheritDoc}
     */
    public static function getMap()
    {
        return array_merge([
            new IntegerField("type_id", [
                'primary' => true
            ]),
            new ReferenceField("type", __NAMESPACE__ . "\\AddressTypeTable", ["=this.type_id" => "ref.id"]),
            new StringField("postal_code"),
            new StringField("region"),
            new StringField("city"),
            new StringField("street"),
            new StringField("house"),
            new StringField("building"),
            new StringField("flat"),
            new GuidField("fias_code"),
            new StringField("kladr_code"),
            new BooleanField("is_active")
        ], parent::getMap());
    }

    /**
     * {@inheritDoc}
     */
    protected static function getIndexes()
    {
        return [
            new Index("idx_address_entity_most", ['owner_id', 'type_id']),
            new Index('idx_is_active', ['is_active'])
        ];
    }
}