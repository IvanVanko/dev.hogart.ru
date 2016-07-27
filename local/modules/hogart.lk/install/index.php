<?php

/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 27/07/16
 * Time: 18:31
 */
class hogart_lk extends CModule
{
    public $MODULE_ID = "hogart.lk";
    public $MODULE_NAME = "Мудуль ЛК";
    public $MODULE_DESCRIPTION = "Модуль личного кабинета компании Хогарт";
    public $PARTNER_NAME = "Oldschool";
    public $PARTNER_URI = "http://oldschool.ru";
    
    public $MODULE_GROUP_RIGHTS = "Y";

    /**
     * @inheritDoc
     */
    public function __construct()
    {
        $arModuleVersion = array();

        include(__DIR__ . "/version.php");

        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
    }

    function InstallDB()
    {
        CModule::IncludeModule($this->MODULE_ID);
        \Hogart\Lk\Entity\HogartCompanyTable::createTableIfNotExists();
        \Hogart\Lk\Entity\ClientCompanyTable::createTableIfNotExists();
        return true;
    }

    function UnInstallDB()
    {
        CModule::IncludeModule($this->MODULE_ID);
        \Hogart\Lk\Entity\HogartCompanyTable::dropTableIfExists();
        \Hogart\Lk\Entity\ClientCompanyTable::dropTableIfExists();
    }

    function DoInstall()
    {
        RegisterModule($this->MODULE_ID);
        CopyDirFiles(__DIR__ . "/admin", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin");
        $this->InstallDB();
    }

    function DoUninstall()
    {
        $this->UnInstallDB();
        DeleteDirFiles(__DIR__ . "/admin", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin");
        UnRegisterModule($this->MODULE_ID);
    }
}
