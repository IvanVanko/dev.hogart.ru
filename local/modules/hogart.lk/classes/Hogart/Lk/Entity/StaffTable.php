<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 29/07/16
 * Time: 16:38
 */

namespace Hogart\Lk\Entity;


use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Entity\StringField;
use Hogart\Lk\Field\GuidField;

class StaffTable extends AbstractEntity
{
    /**
     * @inheritDoc
     */
    public static function getTableName()
    {
        return "h_staff";
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
            new StringField("name"),
            new StringField("last_name"),
            new StringField("middle_name"),
            new IntegerField("chief_id"),
            new ReferenceField("chief", "StaffTable", ["=this.chief_id" => "ref.id"]),
            new GuidField("photo_guid"),
            new ReferenceField("photo", "Bitrix\\Iblock\\ElementTable", ["=this.photo_guid" => "ref.XML_ID"]),
            new IntegerField("branch")
        ];
    }
}
