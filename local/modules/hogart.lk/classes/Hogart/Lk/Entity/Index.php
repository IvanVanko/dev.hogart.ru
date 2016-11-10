<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 28/07/16
 * Time: 12:37
 */

namespace Hogart\Lk\Entity;
/**
 * Класс индекса таблицы Бд
 * @package Hogart\Lk\Entity
 */
class Index
{
    /** @var string  */
    protected $name;
    /** @var array  */
    protected $columns;

    /**
     * Конструктор класса
     * @param string $name
     * @param array $columns
     */
    public function __construct($name, $columns)
    {
        $this->name = $name;
        $this->columns = $columns;
    }

    /**
     * Имя индекса
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Получить поля индекса
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }
}
