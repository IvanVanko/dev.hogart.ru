<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 29/07/16
 * Time: 16:38
 */

namespace Hogart\Lk\Entity;


use Bitrix\Main\Entity\BooleanField;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Entity\StringField;
use Hogart\Lk\Field\GuidField;

class AddressTable extends AbstractEntity
{
    /**
     * @inheritDoc
     */
    public static function getTableName()
    {
        return "h_address";
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
            new IntegerField("type_id"),
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

}