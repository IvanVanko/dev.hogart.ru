<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 27/07/16
 * Time: 19:39
 */

namespace Hogart\Lk\Entity;


use Bitrix\Main\DB\SqlExpression;
use Bitrix\Main\Entity;
use Hogart\Lk\Field\GuidField;

/**
 * Таблица Компании Хогарт
 * @package Hogart\Lk\Entity
 */
class HogartCompanyTable extends AbstractEntity
{
    
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return 'h_hogart_company';
    }

    /**
     * {@inheritDoc}
     */
    public static function getMap()
    {
        return [
            new Entity\IntegerField("id", [
                'primary' => true,
                'autocomplete' => true
            ]),
            new GuidField("guid_id"),
            new Entity\StringField("name"),
            new Entity\StringField("inn"),
            new Entity\StringField("kpp"),
            new Entity\IntegerField("chief_id"),
            new Entity\ReferenceField("chief", "StaffTable", ["=this.chief_id" => "ref.id"]),
            new Entity\BooleanField("is_active"),
            new Entity\ReferenceField("main_payment_account", __NAMESPACE__ . "\\PaymentAccountRelationTable", ["=this.id" => "ref.owner_id", "=ref.is_main" => new SqlExpression('?i', true), "=ref.owner_type" => new SqlExpression('?i', PaymentAccountRelationTable::OWNER_TYPE_CLIENT_COMPANY)]),
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected static function getIndexes()
    {
        return [
            new Index("idx_guid_id", ["guid_id" => 36]),
            new Index('idx_is_active', ['is_active']),
        ];
    }

    public static function showFullName($company, $prefix = '')
    {
        return vsprintf("%s, ИНН %s, КПП %s", [$company[$prefix . "name"], $company[$prefix . "inn"], $company[$prefix . "kpp"]]);
    }
}
