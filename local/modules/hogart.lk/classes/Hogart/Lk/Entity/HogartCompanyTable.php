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

class HogartCompanyTable extends AbstractEntity
{
    
    /**
     * @inheritDoc
     */
    public static function getTableName()
    {
        return 'h_hogart_company';
    }

    /**
     * @inheritDoc
     */
    public static function getMap()
    {
        return [
            new Entity\IntegerField("id", [
                'primary' => true,
                'autocomplete' => true
            ]),
            new GuidField("hogart_id"),
            new Entity\StringField("name"),
            new Entity\StringField("inn"),
            new Entity\StringField("kpp"),
            new Entity\IntegerField("chief_id"),
            new Entity\ReferenceField("chief", "Hogart\\Lk\\Entity\\ContactInfoTable", ["=this.chief_id" => "ref.id"]),
            new Entity\BooleanField("is_active")
        ];
    }

    /**
     * @inheritDoc
     */
    protected static function getIndexes()
    {
        return [
            new Index("idx_hogart_id", ["hogart_id" => 36])
        ];
    }
}
