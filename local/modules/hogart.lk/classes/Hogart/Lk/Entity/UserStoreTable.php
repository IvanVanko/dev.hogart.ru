<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 28/07/16
 * Time: 17:07
 */

namespace Hogart\Lk\Entity;


use Bitrix\Main\Entity\BooleanField;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
use Hogart\Lk\Field\GuidField;

class UserStoreTable extends AbstractEntity
{
    /**
     * @inheritDoc
     */
    public static function getTableName()
    {
        return "h_user_store";
    }

    /**
     * @inheritDoc
     */
    public static function getMap()
    {
        return [
            new GuidField("store_guid", ['primary' => true]),
            new IntegerField("user_id", ['primary' => true]),
            new ReferenceField("store", "Bitrix\\Catalog\\StoreTable", ["=this.store_guid" => "ref.XML_ID"]),
            new ReferenceField("user", "Bitrix\\Main\\UserTable", ["=this.user_id" => "ref.ID"]),
            new BooleanField("is_main", [
                'default_value' => false
            ]),
        ];
    }
}