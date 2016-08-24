<?php

/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 24/08/16
 * Time: 14:51
 */
Class hogart_main extends CModule
{
    var $MODULE_ID = "hogart.main";

    var $MODULE_NAME = "Модуль настроек Хогарт";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_DESCRIPTION = "Модуль для специфического функционала сайта Хогарт";
    var $PARTNER_NAME = "Oldschool";
    var $PARTNER_URI = "http://oldschool.ru";

    public $MODULE_GROUP_RIGHTS = "Y";
    
    function hogart() {
        $arModuleVersion = [];
        include(__DIR__ . "/version.php");
        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
    }

    function DoInstall()
    {
        RegisterModule($this->MODULE_ID);
        RegisterModuleDependences("main", "OnEndBufferContent", $this->MODULE_ID, "Hogart\\Main\\Events", "GTMEnable");
    }

    function DoUninstall()
    {
        UnRegisterModuleDependences("main", "OnEndBufferContent", $this->MODULE_ID, "Hogart\\Main\\Events", "GTMEnable");
        UnRegisterModule($this->MODULE_ID);
    }


}