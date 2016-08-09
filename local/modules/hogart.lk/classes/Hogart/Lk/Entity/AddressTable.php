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
class AddressTable extends AbstractEntity
{
    /** Владелец - Компания клиента */
    const OWNER_TYPE_COMPANY = 1;
    /** Владелец - Компания Хогарт */
    const OWNER_TYPE_HOGART_COMPANY = 2;
    /** Владелец - Склад */
    const OWNER_TYPE_STORE = 3;

    /**
     * Связи, используемые при запросах
     * @var array
     */
    public static $types = [
        self::OWNER_TYPE_COMPANY => [
            "name" => "company",
            "table" => __NAMESPACE__ . "\\CompanyTable",
            "rel" => "guid_id",
            "rel_id" => "id"
        ],
        self::OWNER_TYPE_HOGART_COMPANY => [
            "name" => "hogart_company",
            "table" => __NAMESPACE__ . "\\HogartCompanyTable",
            "rel" => "guid_id",
            "rel_id" => "id"
        ],
        self::OWNER_TYPE_STORE => [
            "name" => "store",
            "table" => "Bitrix\\Catalog\\StoreTable",
            "rel" => "XML_ID",
            "rel_id" => "ID"
        ]
    ];

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
        return [
            new IntegerField("owner_id", ['primary' => true]),
            new EnumField("owner_type", [
                'primary' => true,
                'values' => [
                    self::OWNER_TYPE_COMPANY,
                    self::OWNER_TYPE_HOGART_COMPANY,
                    self::OWNER_TYPE_STORE,
                ]
            ]),
            new ReferenceField(self::$types[self::OWNER_TYPE_COMPANY]['name'], self::$types[self::OWNER_TYPE_COMPANY]['table'], ["=this.owner_id" => "ref.id", "=this.owner_type" => new SqlExpression('?i', self::OWNER_TYPE_COMPANY)]),
            new ReferenceField(self::$types[self::OWNER_TYPE_HOGART_COMPANY]['name'], self::$types[self::OWNER_TYPE_HOGART_COMPANY]['table'], ["=this.owner_id" => "ref.id", "=this.owner_type" => new SqlExpression('?i', self::OWNER_TYPE_HOGART_COMPANY)]),
            new ReferenceField(self::$types[self::OWNER_TYPE_STORE]['name'], self::$types[self::OWNER_TYPE_STORE]['table'], ["=this.owner_id" => "ref.ID", "=this.owner_type" => new SqlExpression('?i', self::OWNER_TYPE_STORE)]),
            new BooleanField("is_main", [
                'default_value' => false
            ]),
            new IntegerField("type_id", [
                'primary' => true
            ]),
            new ReferenceField("type", "AddressTypeTable", ["=this.type_id" => "ref.id"]),
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
        ];
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