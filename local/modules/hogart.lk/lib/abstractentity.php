<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 27/07/16
 * Time: 21:52
 */

namespace Hogart\Lk;

use Bitrix\Main\Entity;

class AbstractEntity extends Entity\DataManager
{
    /**
     * @return bool
     */
    public static function createTableIfNotExists()
    {
        if (!self::getEntity()->getConnection()->isTableExists(self::getTableName())) {
            self::getEntity()->createDbTable();
            return self::getEntity()->getConnection()->isTableExists(self::getTableName());
        }
        return false;
    }

    /**
     * @return bool
     */
    public static function dropTableIfExists()
    {
        if (self::getEntity()->getConnection()->isTableExists(self::getTableName())) {
            self::getEntity()->getConnection()->dropTable(self::getTableName());
            return !self::getEntity()->getConnection()->isTableExists(self::getTableName());
        }
        return false;
    }
}