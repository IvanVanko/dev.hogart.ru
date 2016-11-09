<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 25/09/2016
 * Time: 18:09
 */

namespace Hogart\Lk\PhpExcel;


class SkuReadFilter implements \PHPExcel_Reader_IReadFilter
{
    protected $skuColumn;
    protected $countColumn;

    /**
     * SkuReader constructor.
     * @param $skuColumn
     * @param $countColumn
     */
    public function __construct($skuColumn, $countColumn)
    {
        $this->skuColumn = $skuColumn;
        $this->countColumn = $countColumn;
    }

    /**
     * Should this cell be read?
     *
     * @param    $column        String column index
     * @param    $row            int index
     * @param    $worksheetName    string worksheet name
     * @return    boolean
     */
    public function readCell($column, $row, $worksheetName = '')
    {
        return in_array(strtolower($column), [strtolower($this->skuColumn), strtolower($this->countColumn)]);
    }
}
