<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 27/07/16
 * Time: 21:52
 */

// @todo Пересоздать сущности (т.к. добавлены индексы)
namespace Hogart\Lk\Entity;

use Bitrix\Main\Entity;

abstract class AbstractEntity extends Entity\DataManager
{
    /**
     * @return array|Index[]
     */
    protected static function getIndexes()
    {
        return null;
    }

    /**
     * @return bool
     */
    public static function createTableIfNotExists()
    {
        if (!self::getEntity()->getConnection()->isTableExists(static::getTableName())) {
            self::getEntity()->createDbTable();
            self::createIndexes();
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

    public static function createIndexes()
    {
        /** @var Index $index */
        foreach (static::getIndexes() as $index) {
            $columnNames = [];
            $sqlHelper = self::getEntity()->getConnection()->getSqlHelper();

            foreach ($index->getColumns() as $columnName => $columnSize)
            {
                if (is_int($columnName)) {
                    $columnNames[] = "{$sqlHelper->quote($columnSize)}";
                } else {
                    $columnNames[] = "{$sqlHelper->quote($columnName)}({$columnSize})";
                }
            }
            $sql = 'CREATE INDEX '.$sqlHelper->quote($index->getName()).' ON '.$sqlHelper->quote(static::getTableName()).' ('.join(', ', $columnNames).')';
            self::getEntity()->getConnection()->query($sql);
        }
    }

    /**
     * Получить данные по записи по полю
     * @param $field_name
     * @param $field_value
     * @return array|false
     */
    public static function getByField($field_name, $field_value)
    {
        return static::getList([
            'filter'=>[
                "={$field_name}" => $field_value
            ]
        ])->fetch();
    }

    /**
     * @param $data
     * @return Entity\AddResult
     * @throws \Exception
     */
    public static function replace($data)
    {
        $primary = static::getEntity()->getPrimaryArray();
        $id = [];
        foreach ($primary as $key) {
            $id[$key] = $data[$key];
        }
        static::delete($id);
        return static::add($data);
    }

    /**
     * @param $data
     * @param $field
     * @return Entity\AddResult|Entity\UpdateResult
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Exception
     */
    public static function createOrUpdateByField($data, $field)
    {
        $row = static::getList([
            'filter' => [
                "={$field}" => $data[$field]
            ]
        ])->fetch();
        if (!empty($row)) {
            $primary = static::getEntity()->getPrimaryArray();
            $id = [];
            foreach ($primary as $key) {
                $id[$key] = $row[$key];
            }
            $result = static::update($id, $data);
        } else {
            $result = static::add($data);
        }
        
        return $result;
    }
}