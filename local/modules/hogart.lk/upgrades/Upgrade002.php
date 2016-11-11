<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 15/08/16
 * Time: 16:01
 */

namespace Hogart\Lk\Upgrade;


class Upgrade002 extends AbstractUpgrade
{
    public function doUpgrade()
    {
        CopyDirFiles(Module::getModuleDir() . "/install/components", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/components", true, true);
        RegisterModuleDependences("iblock", "OnAfterIBlockElementAdd", "hogart.lk", "Hogart\\Lk\\Events", "OnAfterIBlockElementAdd");
        RegisterModuleDependences("iblock", "OnAfterIBlockElementUpdate", "hogart.lk", "Hogart\\Lk\\Events", "OnAfterIBlockElementUpdate");
        RegisterModuleDependences("iblock", "OnAfterIBlockElementDelete", "hogart.lk", "Hogart\\Lk\\Events", "OnAfterIBlockElementDelete");
    }
}