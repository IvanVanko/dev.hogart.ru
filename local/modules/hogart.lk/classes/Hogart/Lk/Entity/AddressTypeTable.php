<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 01/08/16
 * Time: 14:56
 */

namespace Hogart\Lk\Entity;


use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Hogart\Lk\Field\GuidField;

class AddressTypeTable extends AbstractEntity
{
    /**
     * @inheritDoc
     */
    public static function getTableName()
    {
        return "h_address_type";
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
            new StringField("name"),
            new GuidField("guid_id")
        ];
    }

    /**
     * @inheritDoc
     */
    protected static function getIndexes()
    {
        return [
            new Index("idx_guid_id", ["guid_id" => 36])
        ];
    }

}
