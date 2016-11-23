<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 15/08/16
 * Time: 16:01
 */

namespace Hogart\Lk\Upgrade;


use Hogart\Lk\Entity\OrderEditTable;
use Hogart\Lk\Entity\OrderItemEditRelTable;
use Hogart\Lk\Entity\OrderItemEditTable;

class Upgrade003 extends AbstractUpgrade
{
    public function doUpgrade()
    {
        OrderEditTable::createTableIfNotExists();
        OrderItemEditTable::createTableIfNotExists();
        OrderItemEditRelTable::createTableIfNotExists();
    }
}