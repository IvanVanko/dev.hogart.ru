<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 15/08/16
 * Time: 16:16
 * 
 * @var $this CBitrixComponent
 */
if (!$this->initComponentTemplate())
    return;

$logger = (new \Hogart\Lk\Logger\BitrixLogger($this->getName(), \Hogart\Lk\Logger\BitrixLogger::STACK_FULL));
global $APPLICATION;

if (!empty($_SESSION["ACCOUNT_ID"]) && !empty($_REQUEST)) {
    switch ($_REQUEST['action']) {
        case 'change-password':
            global $USER;
            $APPLICATION->RestartBuffer();
            $email = $USER->GetEmail();
            try {
                $accountExchange = (new \Hogart\Lk\Exchange\RabbitMQ\Exchange\AccountExchange())->useConsumer(\Hogart\Lk\Exchange\RabbitMQ\Consumer::getInstance());
                $accountExchange->getExchange()->publish($email, $accountExchange->getPublishKey('send_password'), AMQP_NOPARAM, ['delivery_mode' => 2]);
                echo \Bitrix\Main\Web\Json::encode([]);
            } catch (Exception $e) {
                echo \Bitrix\Main\Web\Json::encode([
                    $e->getMessage(),
                    $e->getCode(),
                    get_class($e)
                ]);
                $logger->error(get_class($e) . ": ". $e->getMessage());
            }
            die();
            break;
        case 'add-store':
            foreach ($_POST['stores'] as $store_guid) {
                \Hogart\Lk\Entity\AccountStoreRelationTable::replace([
                    "account_id" => $_SESSION["ACCOUNT_ID"],
                    "store_guid" => $store_guid
                ]);
            }
            LocalRedirect($APPLICATION->GetCurPage());
            break;
        case 'add-contact':
            $result = \Hogart\Lk\Entity\ContactTable::createOrUpdateByField([
                'name' => $_POST['name'],
                'last_name' => $_POST['last_name'],
                'middle_name' => $_POST['middle_name'],
                'is_active' => true
            ], 'hash');
            if (($contact_id =$result->getId())) {
                \Hogart\Lk\Entity\ContactRelationTable::add([
                    'contact_id' => $contact_id,
                    'owner_id' => $_SESSION["ACCOUNT_ID"],
                    'owner_type' => \Hogart\Lk\Entity\ContactRelationTable::OWNER_TYPE_ACCOUNT
                ]);
                if ($_POST['email']) {
                    $ciR = \Hogart\Lk\Entity\ContactInfoTable::add([
                        'owner_id' => $contact_id,
                        'owner_type' => \Hogart\Lk\Entity\ContactInfoTable::OWNER_TYPE_CONTACT,
                        'info_type' => \Hogart\Lk\Entity\ContactInfoTable::TYPE_EMAIL,
                        'value' => $_POST['email'],
                        'is_active' => true
                    ]);
                }
                if ($_POST['phone']) {
                    foreach ($_POST['phone'] as $kind => $phone) {
                        if (empty($phone)) continue;
                        $ciR = \Hogart\Lk\Entity\ContactInfoTable::add([
                            'owner_id' => $contact_id,
                            'owner_type' => \Hogart\Lk\Entity\ContactInfoTable::OWNER_TYPE_CONTACT,
                            'info_type' => \Hogart\Lk\Entity\ContactInfoTable::TYPE_PHONE,
                            'phone_kind' => intval($kind),
                            'value' => $phone,
                            'is_active' => true
                        ]);
                    }
                }
            }
            LocalRedirect($APPLICATION->GetCurPage());
            break;
        case 'edit-contact':
            $result = \Hogart\Lk\Entity\ContactTable::update($_POST['id'], [
                'name' => $_POST['name'],
                'last_name' => $_POST['last_name'],
                'middle_name' => $_POST['middle_name'],
                'is_active' => true
            ]);
            if (($contact_id =$result->getId())) {
                \Hogart\Lk\Entity\ContactRelationTable::replace([
                    'contact_id' => $contact_id,
                    'owner_id' => $_SESSION["ACCOUNT_ID"],
                    'owner_type' => \Hogart\Lk\Entity\ContactRelationTable::OWNER_TYPE_ACCOUNT
                ]);
                \Hogart\Lk\Entity\ContactInfoTable::delete([
                    'owner_id' => $contact_id,
                    'owner_type' => \Hogart\Lk\Entity\ContactInfoTable::OWNER_TYPE_CONTACT,
                    'info_type' => \Hogart\Lk\Entity\ContactInfoTable::TYPE_EMAIL
                ]);
                
                \Hogart\Lk\Entity\ContactInfoTable::delete([
                    'owner_id' => $contact_id,
                    'owner_type' => \Hogart\Lk\Entity\ContactInfoTable::OWNER_TYPE_CONTACT,
                    'info_type' => \Hogart\Lk\Entity\ContactInfoTable::TYPE_PHONE,
                    'phone_kind' => \Hogart\Lk\Entity\ContactInfoTable::PHONE_KIND_MOBILE
                ]);
                \Hogart\Lk\Entity\ContactInfoTable::delete([
                    'owner_id' => $contact_id,
                    'owner_type' => \Hogart\Lk\Entity\ContactInfoTable::OWNER_TYPE_CONTACT,
                    'info_type' => \Hogart\Lk\Entity\ContactInfoTable::TYPE_PHONE,
                    'phone_kind' => \Hogart\Lk\Entity\ContactInfoTable::PHONE_KIND_STATIC
                ]);
                
                if ($_POST['email']) {
                    $ciR = \Hogart\Lk\Entity\ContactInfoTable::add([
                        'owner_id' => $contact_id,
                        'owner_type' => \Hogart\Lk\Entity\ContactInfoTable::OWNER_TYPE_CONTACT,
                        'info_type' => \Hogart\Lk\Entity\ContactInfoTable::TYPE_EMAIL,
                        'value' => $_POST['email'],
                        'is_active' => true
                    ]);
                }
                if ($_POST['phone']) {
                    foreach ($_POST['phone'] as $kind => $phone) {
                        if (empty($phone)) continue;
                        $ciR = \Hogart\Lk\Entity\ContactInfoTable::add([
                            'owner_id' => $contact_id,
                            'owner_type' => \Hogart\Lk\Entity\ContactInfoTable::OWNER_TYPE_CONTACT,
                            'info_type' => \Hogart\Lk\Entity\ContactInfoTable::TYPE_PHONE,
                            'phone_kind' => intval($kind),
                            'value' => $phone,
                            'is_active' => true
                        ]);
                    }
                }
            }
            LocalRedirect($APPLICATION->GetCurPage());
            break;
    }
    if (!empty($_REQUEST['fav_store'])) {
        \Hogart\Lk\Entity\AccountTable::update($_SESSION['ACCOUNT_ID'], [
            "main_store_id" => $_REQUEST['fav_store']
        ]);
    }
    if (!empty($_REQUEST['remove_store'])) {
        \Hogart\Lk\Entity\AccountStoreRelationTable::delete([
            "account_id" => $_SESSION['ACCOUNT_ID'],
            "store_guid" => $_REQUEST['remove_store']
        ]);
    }
    if (!empty($_REQUEST['remove_contact'])) {
        \Hogart\Lk\Entity\ContactRelationTable::delete([
            "contact_id" => $_REQUEST['remove_contact'],
            "owner_id" => $_SESSION['ACCOUNT_ID'],
            "owner_type" => \Hogart\Lk\Entity\ContactRelationTable::OWNER_TYPE_ACCOUNT
        ]);
    }

    // Постановка задачи на отправку аккаунта в КИС
    try {
        $accountExchange = (new \Hogart\Lk\Exchange\RabbitMQ\Exchange\AccountExchange())->useConsumer(\Hogart\Lk\Exchange\RabbitMQ\Consumer::getInstance());
        $accountExchange->getExchange()->publish("", $accountExchange->getPublishKey('set'), AMQP_NOPARAM, ['delivery_mode' => 2]);
    } catch (Exception $e) {
        $logger->error(get_class($e) . ": ". $e->getMessage());
    }
}

if ($this->startResultCache()) {
    if (!CModule::IncludeModule("hogart.lk")) {
        $this->abortResultCache();
        ShowError("Не установлен модуль \"Модуль личного кабинета компании Хогарт\"");
        return;
    }
    global $CACHE_MANAGER, $USER_FIELD_MANAGER;

    $account = \Hogart\Lk\Entity\AccountTable::getAccountById($_SESSION["ACCOUNT_ID"]);
    $account['stores'] = \Hogart\Lk\Entity\AccountStoreRelationTable::getByAccountId($account['id']);
    $account['contacts'] = \Hogart\Lk\Entity\ContactRelationTable::getAccountContacts($account['id']);
    $account['managers'] = \Hogart\Lk\Entity\StaffRelationTable::getManagersByAccountId($account['id']);
    $account['sub_accounts'] = \Hogart\Lk\Entity\AccountTable::getSubAccounts($account['id']);
    $account['is_general'] = \Hogart\Lk\Entity\AccountTable::isGeneralAccount($account['id']);
    $arResult['account'] = $account;

    $arResult['stores'] = array_reduce(\Hogart\Lk\Entity\StoreTable::getList([
        'filter' => [
            '=ACTIVE' => true,
            '=UF_TRANSIT' => 0 // убираем транзитные склады
        ],
    ])->fetchAll(), function ($result, $item) { $result[$item['ID']] = $item; return $result; }, []);

    $arResult['av_stores'] = $arResult['stores'];
    foreach ($account['stores'] as $store) {
        unset($arResult['av_stores'][$store['store_ID']]);
    }
    
    if (defined("BX_COMP_MANAGED_CACHE"))
    {
        $CACHE_MANAGER->StartTagCache($this->getCachePath());
        $CACHE_MANAGER->RegisterTag("hogart_lk_account_" . $account['id']);
        $CACHE_MANAGER->EndTagCache();
    }
    
    $this->includeComponentTemplate();

    return $account['id'];
}