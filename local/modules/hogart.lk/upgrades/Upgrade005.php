<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 15/08/16
 * Time: 16:01
 */

namespace Hogart\Lk\Upgrade;


use Hogart\Lk\Entity\ReportTable;

class Upgrade005 extends AbstractUpgrade
{
    public function doUpgrade()
    {
        CopyDirFiles(Module::getModuleDir() . "/install/components", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/components", true, true);
    }
}