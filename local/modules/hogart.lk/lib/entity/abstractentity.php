<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 27/07/16
 * Time: 21:52
 */

namespace Hogart\Lk\Entity;

use Bitrix\Main\Entity;

class AbstractEntity extends Entity\DataManager
{
    /**
     * @return bool
     */
    public static function createTableIfNotExists()
    {
        if (!self::getEntity()->getConnection()->isTableExists(static::getTableName())) {
            self::getEntity()->createDbTable();
            return self::getEntity()->getConnection()->isTableExists(static::getTableName());
        }
        return false;
    }

    /**
     * @return bool
     */
    public static function dropTableIfExists()
    {
        if (self::getEntity()->getConnection()->isTableExists(static::getTableName())) {
            self::getEntity()->getConnection()->dropTable(static::getTableName());
            return !self::getEntity()->getConnection()->isTableExists(static::getTableName());
        }
        return false;
    }
}