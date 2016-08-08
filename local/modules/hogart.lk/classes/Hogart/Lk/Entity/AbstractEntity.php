<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 27/07/16
 * Time: 21:52
 */

// @todo Пересоздать индексы
namespace Hogart\Lk\Entity;

use Bitrix\Main\Entity;

/**
 * Асбтрактный класс таблицы Бд
 * @package Hogart\Lk\Entity
 */
abstract class AbstractEntity extends Entity\DataManager
{
    /**
     * Получение схемы индексов таблицы
     * @return array|Index[]
     */
    protected static function getIndexes()
    {
        return null;
    }

    /**
     * Создание таблицы в Бд, при условии, что таковой еще не существует
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
     * Удалить таблицу из Бд, если таковая существует
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

    /**
     * Создание индекса для таблцы в Бд
     */
    public static function createIndexes()
    {
        $sqlHelper = self::getEntity()->getConnection()->getSqlHelper();
        /** @var Index $index */
        foreach (static::getIndexes() as $index) {
            $columnNames = [];
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
     * Удаление индексов
     */
    public static function dropIndexes()
    {
        $sqlHelper = self::getEntity()->getConnection()->getSqlHelper();
        $rs = self::getEntity()->getConnection()->query("SHOW INDEX FROM `" . $sqlHelper->forSql(static::getTableName()) . "`");
        
        if (!$rs)
            return null;

        $indexes = array();
        while ($ar = $rs->fetch())
        {
            $indexes[] = $ar["Key_name"];
        }
        
        /** @var Index $index */
        foreach (static::getIndexes() as $index) {
            if (in_array($index->getName(), $indexes)) {
                $sql = 'DROP INDEX '.$sqlHelper->quote($index->getName()).' ON '.$sqlHelper->quote(static::getTableName());
                self::getEntity()->getConnection()->query($sql);
            }
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
     * Заменить запись в таблице
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
     * Создание или обновление записи в таблице
     * @param array $data данные которые добавляем|обновляем
     * @param string|array $fields одно или несколько полей для поиска
     * @return Entity\AddResult|Entity\UpdateResult
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Exception
     */
    public static function createOrUpdateByField($data, $fields)
    {
        $row = static::getList([
            'filter' => (is_string($fields) ? ["={$fields}" => $data[$fields]] : $fields)
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