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
    public $MODULE_NAME = "Модуль ЛК";
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
        \Hogart\Lk\Entity\CartTable::createTableIfNotExists();
        \Hogart\Lk\Entity\CartItemTable::createTableIfNotExists();
        \Hogart\Lk\Entity\CompanyDiscountTable::createTableIfNotExists();
        \Hogart\Lk\Entity\CompanyTable::createTableIfNotExists();
        \Hogart\Lk\Entity\ContactInfoTable::createTableIfNotExists();
        \Hogart\Lk\Entity\ContactRelationTable::createTableIfNotExists();
        \Hogart\Lk\Entity\ContactTable::createTableIfNotExists();
        \Hogart\Lk\Entity\ContractTable::createTableIfNotExists();
        \Hogart\Lk\Entity\HogartCompanyTable::createTableIfNotExists();
        \Hogart\Lk\Entity\KindOfActivityTable::createTableIfNotExists();
        \Hogart\Lk\Entity\OrderEventTable::createTableIfNotExists();
        \Hogart\Lk\Entity\OrderItemTable::createTableIfNotExists();
        \Hogart\Lk\Entity\OrderPaymentTable::createTableIfNotExists();
        \Hogart\Lk\Entity\OrderTable::createTableIfNotExists();
        \Hogart\Lk\Entity\OrderRTUTable::createTableIfNotExists();
        \Hogart\Lk\Entity\OrderRTUItemTable::createTableIfNotExists();
        \Hogart\Lk\Entity\OrderEditTable::createTableIfNotExists();
        \Hogart\Lk\Entity\OrderItemEditTable::createTableIfNotExists();
        \Hogart\Lk\Entity\OrderItemEditRelTable::createTableIfNotExists();
        \Hogart\Lk\Entity\PdfTable::createTableIfNotExists();
        \Hogart\Lk\Entity\PaymentAccountRelationTable::createTableIfNotExists();
        \Hogart\Lk\Entity\PaymentAccountTable::createTableIfNotExists();
        \Hogart\Lk\Entity\RTUItemTable::createTableIfNotExists();
        \Hogart\Lk\Entity\RTUTable::createTableIfNotExists();
        \Hogart\Lk\Entity\StaffRelationTable::createTableIfNotExists();
        \Hogart\Lk\Entity\StaffTable::createTableIfNotExists();
        \Hogart\Lk\Entity\StoreAmountTable::createTableIfNotExists();
        \Hogart\Lk\Entity\FlashMessagesTable::createTableIfNotExists();

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
        \Hogart\Lk\Entity\CartTable::dropTableIfExists();
        \Hogart\Lk\Entity\CartItemTable::dropTableIfExists();
        \Hogart\Lk\Entity\CompanyDiscountTable::dropTableIfExists();
        \Hogart\Lk\Entity\CompanyTable::dropTableIfExists();
        \Hogart\Lk\Entity\ContactInfoTable::dropTableIfExists();
        \Hogart\Lk\Entity\ContactRelationTable::dropTableIfExists();
        \Hogart\Lk\Entity\ContactTable::dropTableIfExists();
        \Hogart\Lk\Entity\ContractTable::dropTableIfExists();
        \Hogart\Lk\Entity\HogartCompanyTable::dropTableIfExists();
        \Hogart\Lk\Entity\KindOfActivityTable::dropTableIfExists();
        \Hogart\Lk\Entity\OrderEventTable::dropTableIfExists();
        \Hogart\Lk\Entity\OrderItemTable::dropTableIfExists();
        \Hogart\Lk\Entity\OrderPaymentTable::dropTableIfExists();
        \Hogart\Lk\Entity\OrderTable::dropTableIfExists();
        \Hogart\Lk\Entity\OrderRTUTable::dropTableIfExists();
        \Hogart\Lk\Entity\OrderRTUItemTable::dropTableIfExists();
        \Hogart\Lk\Entity\OrderEditTable::dropTableIfExists();
        \Hogart\Lk\Entity\OrderItemEditTable::dropTableIfExists();
        \Hogart\Lk\Entity\OrderItemEditRelTable::dropTableIfExists();
        \Hogart\Lk\Entity\PdfTable::dropTableIfExists();
        \Hogart\Lk\Entity\PaymentAccountRelationTable::dropTableIfExists();
        \Hogart\Lk\Entity\PaymentAccountTable::dropTableIfExists();
        \Hogart\Lk\Entity\RTUItemTable::dropTableIfExists();
        \Hogart\Lk\Entity\RTUTable::dropTableIfExists();
        \Hogart\Lk\Entity\StaffRelationTable::dropTableIfExists();
        \Hogart\Lk\Entity\StaffTable::dropTableIfExists();
        \Hogart\Lk\Entity\StoreAmountTable::dropTableIfExists();
        \Hogart\Lk\Entity\FlashMessagesTable::dropTableIfExists();
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
            " - Параметры SOAP-сервиса",
            " - Допполнительные параметры"
        ];
        $step = max(1, intval($step));

        $GLOBALS['MODULE_ID'] = $this->MODULE_ID;
        $GLOBALS['MODULE_NAME'] = $this->MODULE_NAME;
        
        if ($step == 1) {
            // Установка необходимых библиотек Composer
            $command = "/usr/bin/php -f " . realpath(dirname(__FILE__) . "/composerExtractor.php");
            exec($command, $output);
        }
        
        if ($step == 4) {
            RegisterModule($this->MODULE_ID);
            CopyDirFiles(__DIR__ . "/admin", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin");
            CopyDirFiles(__DIR__ . "/components", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/components");
            CopyDirFiles(__DIR__ . "/templates", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/templates", true, true);
            $this->InstallDB();

            RegisterModuleDependences("main", "OnAfterUserLogin", "hogart.lk", "Hogart\\Lk\\Events", "OnAfterUserLogin");
            RegisterModuleDependences("main", "OnSendUserInfo", "hogart.lk", "Hogart\\Lk\\Events", "OnSendUserInfo");
            RegisterModuleDependences("main", "OnBeforeEventAdd", "hogart.lk", "Hogart\\Lk\\Events", "OnBeforeEventAdd");

            $this->InstallTasks();
            $this->InstallEvents();

            $upgradeManager = new \Hogart\Lk\Upgrade\UpgradeManager(true);
            $upgradeManager->upgradeReload();
        }
        
        $APPLICATION->IncludeAdminFile("Установка модуля \"{$this->MODULE_NAME}\"{$stepTitles[$step - 1]}", __DIR__ . "/step{$step}.php");
    }

    function InstallEvents()
    {
        $eventHelper = new \Hogart\Lk\Helper\Admin\EventHelper();
        if (($eventId = $eventHelper->addEventTypeIfNotExists("ACCOUNT_NEW_USER", [
            'LID' => 'ru',
            'NAME' => 'Отсылка данных по новому аккаунту',
        ]))) {
            $message =<<<TEXT
Здравствуйте, #ACCOUNT_NAME#!

На ваш почтовый адрес зарегистрирован аккаунт для работы в личном кабинете на сайте #SITE_NAME#

Для начала работы вам необходимо установить пароль. Перейдите, пожалуйста, по следующей ссылке:
http://#SERVER_NAME#/auth/index.php?change_password=yes&lang=ru&USER_CHECKWORD=#CHECKWORD#&USER_LOGIN=#URL_LOGIN#

При возникновении любых вопросов по работе в личном кабинете, на Ваши вопросы ответит менеджер #MANAGER# 
или обратитесь в отдел программного обеспечения компании «Хогарт», телефон +7 (495) 788-11-12 (доб 304), email: 1c@hogart.ru.

Мы благодарим вас за регистрацию и рады нашему сотрудничеству!

Сообщение сгенерировано автоматически.
TEXT;

            $eventHelper->addEventMessageIfNotExists('ACCOUNT_NEW_USER', [
                'SUBJECT' => 'Информационное сообщение сайта #SITE_NAME#',
                'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
                'EMAIL_TO' => '#EMAIL#',
                'BODY_TYPE' => 'text',
                'MESSAGE' => $message
            ]);
        }

	    if (($eventId = $eventHelper->addEventTypeIfNotExists("ACCOUNT_CHANGED_PASSWORD", [
		    'LID' => 'ru',
		    'NAME' => 'Смена пароля аккаунта',
	    ]))) {
		    $message =<<<TEXT
Здравствуйте, #ACCOUNT_NAME#!

На ваш почтовый адрес зарегистрирован аккаунт для работы в личном кабинете на сайте #SITE_NAME#

Ваш пароль успешно изменен.

Ваша регистрационная информация:
Login: #LOGIN#
Статус профиля: #ACTIVE#

При возникновении любых вопросов по работе в личном кабинете, на Ваши вопросы ответит менеджер #MANAGER# 
или обратитесь в отдел программного обеспечения компании «Хогарт», телефон +7 (495) 788-11-12 (доб 304), email: 1c@hogart.ru.

Мы благодарим вас за регистрацию и рады нашему сотрудничеству!

Сообщение сгенерировано автоматически.
TEXT;

		    $eventHelper->addEventMessageIfNotExists('ACCOUNT_CHANGED_PASSWORD', [
			    'SUBJECT' => 'Информационное сообщение сайта #SITE_NAME#',
			    'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
			    'EMAIL_TO' => '#EMAIL#',
			    'BODY_TYPE' => 'text',
			    'MESSAGE' => $message
		    ]);
	    }

        if (($eventId = $eventHelper->addEventTypeIfNotExists(\Hogart\Lk\Helper\Mail\Event::COMPANY_DOC_REQUEST, [
            'LID' => 'ru',
            'NAME' => 'Запрос на получения оригиналов договора',
        ]))) {
            $message =<<<TEXT
Здравствуйте, #MANAGER#!

Клиент #COMPANY_NAME# запросил оригиналы договора #CONTRACT_NAME#.

Запрос оформил(-а) #ACCOUNT_NAME#.

Сообщение сгенерировано автоматически.
TEXT;

            $eventHelper->addEventMessageIfNotExists(\Hogart\Lk\Helper\Mail\Event::COMPANY_DOC_REQUEST, [
                'SUBJECT' => 'Клиент #COMPANY_NAME# запросил оригиналы договора',
                'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
                'EMAIL_TO' => '#MANAGER_EMAIL#',
                'BODY_TYPE' => 'text',
                'MESSAGE' => $message
            ]);
        }

        if (($eventId = $eventHelper->addEventTypeIfNotExists(\Hogart\Lk\Helper\Mail\Event::HOGART_FEEDBACK, [
            'LID' => 'ru',
            'NAME' => 'Запрос от клиента',
        ]))) {
            $message =<<<TEXT
Здравствуйте, #MANAGER#!

Клиент #COMPANY_NAME# задает вопрос:

#MESSAGE#

Сообщение сгенерировано автоматически.
TEXT;

            $eventHelper->addEventMessageIfNotExists(\Hogart\Lk\Helper\Mail\Event::HOGART_FEEDBACK, [
                'SUBJECT' => '#SUBJECT#',
                'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
                'EMAIL_TO' => '#MANAGER_EMAIL#',
                'BODY_TYPE' => 'text',
                'MESSAGE' => $message
            ]);
        }
    }

    function DoUninstall()
    {
        $this->UnInstallTasks();
        UnRegisterModuleDependences("main", "OnAfterUserLogin", "hogart.lk", "Hogart\\Lk\\Events", "OnAfterUserLogin");
        UnRegisterModuleDependences("main", "OnSendUserInfo", "hogart.lk", "Hogart\\Lk\\Events", "OnSendUserInfo");
        UnRegisterModuleDependences("main", "OnBeforeEventAdd", "hogart.lk", "Hogart\\Lk\\Events", "OnBeforeEventAdd");
        $this->UnInstallDB();
        DeleteDirFiles(__DIR__ . "/admin", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin");
        DeleteDirFiles(__DIR__ . "/components", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/components");
        DeleteDirFiles(__DIR__ . "/templates", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/templates");
        UnRegisterModule($this->MODULE_ID);
    }

    function GetModuleTasks()
    {
        return [

        ];
    }
}
