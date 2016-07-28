<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 28/07/16
 * Time: 15:44
 */

namespace Hogart\Lk\Entity;


use Bitrix\Main\Entity\BooleanField;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Hogart\Lk\Field\GuidField;

class ContactTable extends AbstractEntity
{
    /**
     * @inheritDoc
     */
    public static function getTableName()
    {
        return "h_contact";
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
            new GuidField("hogart_id"),
            new StringField("name"),
            new StringField("last_name"),
            new StringField("middle_name"),
            new BooleanField("is_active")
        ];
    }

    /**
     * @inheritDoc
     */
    protected static function getIndexes()
    {
        return [
            new Index("idx_hogart_id", ["hogart_id" => 36]),
        ];
    }
}
