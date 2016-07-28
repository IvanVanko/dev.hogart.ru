<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 28/07/16
 * Time: 12:37
 */

namespace Hogart\Lk\Entity;

class Index
{
    protected $name;
    protected $columns;

    /**
     * Index constructor.
     * @param string $name
     * @param array $columns
     */
    public function __construct($name, $columns)
    {
        $this->name = $name;
        $this->columns = $columns;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }
}
