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
            new GuidField("chief_id")
        ];
    }
}
