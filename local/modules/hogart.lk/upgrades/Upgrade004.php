<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 15/08/16
 * Time: 16:01
 */

namespace Hogart\Lk\Upgrade;


use Hogart\Lk\Entity\ReportTable;

class Upgrade004 extends AbstractUpgrade
{
    public function doUpgrade()
    {
        ReportTable::createTableIfNotExists();
    }
}