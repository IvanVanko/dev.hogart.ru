<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 28/07/16
 * Time: 15:51
 */

namespace Hogart\Lk\Entity;


use Bitrix\Main\DB\SqlExpression;
use Bitrix\Main\Entity\BooleanField;
use Bitrix\Main\Entity\EnumField;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
use Hogart\Lk\Exchange\SOAP\MethodException;

/**
 * Абстрактный класс таблицы связей
 * @package Hogart\Lk\Entity
 */
abstract class AbstractEntityRelation extends AbstractEntity
{
    /** Владелец - Аккаунт клиента */
    const OWNER_TYPE_ACCOUNT = "1";
    /** Владелец - Компания клиента */
    const OWNER_TYPE_CLIENT_COMPANY = "2";
    /** Владелец - Сотрудник компании */
    const OWNER_TYPE_STAFF = "3";
    /** Владелец - Компания Хогарт */
    const OWNER_TYPE_HOGART_COMPANY = "4";
    /** Владелец - Склад */
    const OWNER_TYPE_STORE = "5";
    /** Владелец - Контактное лицо */
    const OWNER_TYPE_CONTACT = "6";
    /** Владелец - Заявка на отгрузку */
    const OWNER_TYPE_ORDER_RTU = "7";

    /**
     * Связи, используемые при запросах
     * @var array
     */
    public static $types = [
        self::OWNER_TYPE_ACCOUNT => [
            "name" => "account",
            "table" => __NAMESPACE__ . "\\AccountTable",
            "rel" => "user_guid_id",
            "rel_id" => "id",
            "error" => MethodException::ERROR_NO_ACCOUNT
        ],
        self::OWNER_TYPE_CLIENT_COMPANY => [
            "name" => "company",
            "table" => __NAMESPACE__ . "\\CompanyTable",
            "rel" => "guid_id",
            "rel_id" => "id",
            "error" => MethodException::ERROR_NO_CLIENT_COMPANY
        ],
        self::OWNER_TYPE_STAFF => [
            "name" => "staff",
            "table" => __NAMESPACE__ . "\\StaffTable",
            "rel" => "guid_id",
            "rel_id" => "id",
            "error" => MethodException::ERROR_NO_STAFF
        ],
        self::OWNER_TYPE_HOGART_COMPANY => [
            "name" => "hogart_company",
            "table" => __NAMESPACE__ . "\\HogartCompanyTable",
            "rel" => "guid_id",
            "rel_id" => "id",
            "error" => MethodException::ERROR_NO_HOGART_COMPANY
        ],
        self::OWNER_TYPE_STORE => [
            "name" => "store",
            "table" => __NAMESPACE__ . "\\StoreTable",
            "rel" => "XML_ID",
            "rel_id" => "ID",
            "error" => MethodException::ERROR_NO_STORE
        ],
        self::OWNER_TYPE_CONTACT => [
            "name" => "contact",
            "table" => __NAMESPACE__ . "\\ContactTable",
            "rel" => "guid_id",
            "rel_id" => "id",
            "error" => MethodException::ERROR_NO_CONTACT
        ],
    ];

    /**
     * @param $owner_id
     * @param $owner_type
     * @param array $filter
     * @param array $select
     * @return array
     */
    public static function getByOwner($owner_id, $owner_type, $filter = [], $select = ['*'])
    {
        $rows = self::getList([
            'filter' => array_merge([
                '=' . (self::$types[$owner_type]['name'] . "." . self::$types[$owner_type]['rel_id']) => $owner_id,
            ], $filter),
            'select' => $select
        ])->fetchAll();

        return $rows;
    }

    /**
     * @param $row
     * @return mixed
     */
    public static function getOwnerRel($row)
    {
        $type = self::$types[$row['owner_type']];
        $owner = self::getOwner($row);
        if (!empty($owner)) {
            return $owner[$type['rel']];
        }
    }

    /**
     * @param $row
     * @return mixed
     */
    public static function getOwner($row)
    {
        $type = self::$types[$row['owner_type']];
        /** @var AbstractEntity $table */
        $table = $type['table'];
        return $table::getByField($type['rel_id'], $row['owner_id']);
    }

    /**
     * {@inheritDoc}
     */
    public static function getMap()
    {
        return [
            new IntegerField("owner_id", ['primary' => true]),
            new ReferenceField(self::$types[self::OWNER_TYPE_CLIENT_COMPANY]['name'], self::$types[self::OWNER_TYPE_CLIENT_COMPANY]['table'], ["=this.owner_id" => "ref.id", "=this.owner_type" => new SqlExpression('?i', self::OWNER_TYPE_CLIENT_COMPANY)]),
            new ReferenceField(self::$types[self::OWNER_TYPE_HOGART_COMPANY]['name'], self::$types[self::OWNER_TYPE_HOGART_COMPANY]['table'], ["=this.owner_id" => "ref.id", "=this.owner_type" => new SqlExpression('?i', self::OWNER_TYPE_HOGART_COMPANY)]),
            new ReferenceField(self::$types[self::OWNER_TYPE_STORE]['name'], self::$types[self::OWNER_TYPE_STORE]['table'], ["=this.owner_id" => "ref.ID", "=this.owner_type" => new SqlExpression('?i', self::OWNER_TYPE_STORE)]),
            new ReferenceField(self::$types[self::OWNER_TYPE_ACCOUNT]['name'], self::$types[self::OWNER_TYPE_ACCOUNT]['table'], ["=this.owner_id" => "ref.id", "=this.owner_type" => new SqlExpression('?i', self::OWNER_TYPE_ACCOUNT)]),
            new ReferenceField(self::$types[self::OWNER_TYPE_STAFF]['name'], self::$types[self::OWNER_TYPE_STAFF]['table'], ["=this.owner_id" => "ref.id", "=this.owner_type" => new SqlExpression('?i', self::OWNER_TYPE_STAFF)]),
            new ReferenceField(self::$types[self::OWNER_TYPE_CONTACT]['name'], self::$types[self::OWNER_TYPE_CONTACT]['table'], ["=this.owner_id" => "ref.id", "=this.owner_type" => new SqlExpression('?i', self::OWNER_TYPE_CONTACT)]),
            new BooleanField("is_main", [
                'default_value' => false
            ]),
            new EnumField("owner_type", [
                'primary' => true,
                'values' => [
                    self::OWNER_TYPE_ACCOUNT,
                    self::OWNER_TYPE_CLIENT_COMPANY,
                    self::OWNER_TYPE_STAFF,
                    self::OWNER_TYPE_HOGART_COMPANY,
                    self::OWNER_TYPE_STORE,
                    self::OWNER_TYPE_CONTACT,
                ]
            ])
        ];
    }
}
