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
        \Hogart\Lk\Entity\AccountCompanyRelationTable::createTableIfNotExists();
        \Hogart\Lk\Entity\AccountStoreRelationTable::createTableIfNotExists();
        \Hogart\Lk\Entity\AccountTable::createTableIfNotExists();
        \Hogart\Lk\Entity\AddressTable::createTableIfNotExists();
        \Hogart\Lk\Entity\AddressTypeTable::createTableIfNotExists();
        \Hogart\Lk\Entity\CompanyDiscountTable::createTableIfNotExists();
        \Hogart\Lk\Entity\CompanyTable::createTableIfNotExists();
        \Hogart\Lk\Entity\ContactInfoTable::createTableIfNotExists();
        \Hogart\Lk\Entity\ContactRelationTable::createTableIfNotExists();
        \Hogart\Lk\Entity\ContactTable::createTableIfNotExists();
        \Hogart\Lk\Entity\ContractTable::createTableIfNotExists();
        \Hogart\Lk\Entity\HogartCompanyTable::createTableIfNotExists();
        \Hogart\Lk\Entity\KindOfActivityTable::createTableIfNotExists();
        \Hogart\Lk\Entity\OrderItemTable::createTableIfNotExists();
        \Hogart\Lk\Entity\OrderPaymentTable::createTableIfNotExists();
        \Hogart\Lk\Entity\OrderTable::createTableIfNotExists();
        \Hogart\Lk\Entity\PaymentAccountRelationTable::createTableIfNotExists();
        \Hogart\Lk\Entity\PaymentAccountTable::createTableIfNotExists();
        \Hogart\Lk\Entity\RTUItemTable::createTableIfNotExists();
        \Hogart\Lk\Entity\RTUTable::createTableIfNotExists();
        \Hogart\Lk\Entity\StaffRelationTable::createTableIfNotExists();
        \Hogart\Lk\Entity\StaffTable::createTableIfNotExists();

        return true;
    }

    function UnInstallDB()
    {
        CModule::IncludeModule($this->MODULE_ID);
        \Hogart\Lk\Entity\AccountCompanyRelationTable::dropTableIfExists();
        \Hogart\Lk\Entity\AccountStoreRelationTable::dropTableIfExists();
        \Hogart\Lk\Entity\AccountTable::dropTableIfExists();
        \Hogart\Lk\Entity\AddressTable::dropTableIfExists();
        \Hogart\Lk\Entity\AddressTypeTable::dropTableIfExists();
        \Hogart\Lk\Entity\CompanyDiscountTable::dropTableIfExists();
        \Hogart\Lk\Entity\CompanyTable::dropTableIfExists();
        \Hogart\Lk\Entity\ContactInfoTable::dropTableIfExists();
        \Hogart\Lk\Entity\ContactRelationTable::dropTableIfExists();
        \Hogart\Lk\Entity\ContactTable::dropTableIfExists();
        \Hogart\Lk\Entity\ContractTable::dropTableIfExists();
        \Hogart\Lk\Entity\HogartCompanyTable::dropTableIfExists();
        \Hogart\Lk\Entity\KindOfActivityTable::dropTableIfExists();
        \Hogart\Lk\Entity\OrderItemTable::dropTableIfExists();
        \Hogart\Lk\Entity\OrderPaymentTable::dropTableIfExists();
        \Hogart\Lk\Entity\OrderTable::dropTableIfExists();
        \Hogart\Lk\Entity\PaymentAccountRelationTable::dropTableIfExists();
        \Hogart\Lk\Entity\PaymentAccountTable::dropTableIfExists();
        \Hogart\Lk\Entity\RTUItemTable::dropTableIfExists();
        \Hogart\Lk\Entity\RTUTable::dropTableIfExists();
        \Hogart\Lk\Entity\StaffRelationTable::dropTableIfExists();
        \Hogart\Lk\Entity\StaffTable::dropTableIfExists();
    }

    function DoInstall()
    {
        global $APPLICATION, $step;
        if (!extension_loaded("amqp")) {
            $APPLICATION->ThrowException("Не загружена PECL библиотека amqp");
            return false;
        }
        if (!extension_loaded("soap")) {
            $APPLICATION->ThrowException("Не загружена библиотека soap");
            return false;
        }
        
        if (ini_get("allow_url_fopen") != 1) {
            $APPLICATION->ThrowException("Директива allow_url_fopen должна быть равно \"On\"");
            return false;
        }

        $stepTitles = [
            " - Параметры RabbitMQ",
            " - Параметры SOAP-сервиса"
        ];
        $step = max(1, intval($step));

        $GLOBALS['MODULE_ID'] = $this->MODULE_ID;
        $GLOBALS['MODULE_NAME'] = $this->MODULE_NAME;
        
        if ($step == 1) {
            // Установка необходимых библиотек Composer
            $command = "/usr/bin/php -f " . realpath(dirname(__FILE__) . "/composerExtractor.php");
            exec($command, $output);
        }
        
        if ($step == 3) {
            RegisterModule($this->MODULE_ID);
            CopyDirFiles(__DIR__ . "/admin", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin");
            $this->InstallDB();
        }
        
        $APPLICATION->IncludeAdminFile("Установка модуля \"{$this->MODULE_NAME}\"{$stepTitles[$step - 1]}", __DIR__ . "/step{$step}.php");
    }

    function DoUninstall()
    {
        $this->UnInstallDB();
        DeleteDirFiles(__DIR__ . "/admin", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin");
        UnRegisterModule($this->MODULE_ID);
    }
}
