<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 28/07/16
 * Time: 17:07
 */

namespace Hogart\Lk\Entity;


use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;

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
            new IntegerField("store_id", ['primary' => true]),
            new IntegerField("user_id", ['primary' => true]),
            new ReferenceField("store", "Bitrix\\Catalog\\StoreTable", ["=this.store_id" => "ref.ID"]),
            new ReferenceField("user", "Bitrix\\Main\\UserTable", ["=this.user_id" => "ref.ID"]),
        ];
    }
}