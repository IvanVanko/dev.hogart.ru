<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 27/07/16
 * Time: 19:39
 */

namespace Hogart\Lk\Entity;


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
            new Entity\BooleanField("is_active")
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
}
