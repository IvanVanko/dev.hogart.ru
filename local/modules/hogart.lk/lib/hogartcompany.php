<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 27/07/16
 * Time: 19:39
 */

namespace Hogart\Lk;


use Bitrix\Main\Entity;

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
            new Entity\StringField("hogart_id", [
                'size' => 36
            ]),
            new Entity\StringField("name"),
            new Entity\StringField("inn"),
            new Entity\StringField("kpp"),
            new Entity\StringField("chief_id", [
                'size' => 36
            ])
        ];
    }
}
