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

if (!empty($_SESSION["ACCOUNT_ID"]) && !empty($_POST)) {
    switch ($_POST['action']) {
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
        case 'add-contact':
            $result = \Hogart\Lk\Entity\ContactTable::add([
                'name' => $_POST['name'],
                'last_name' => $_POST['last_name'],
                'middle_name' => $_POST['middle_name'],
            ]);
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
                        'value' => $_POST['email']
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
                            'value' => $phone
                        ]);
                    }
                }
            }
            LocalRedirect($APPLICATION->GetCurPage());
            break;
    }
}

if ($this->startResultCache()) {
    if (!CModule::IncludeModule("hogart.lk")) {
        $this->abortResultCache();
        ShowError("Не установлен модуль \"Модуль личного кабинета компании Хогарт\"");
        return;
    }
    global $CACHE_MANAGER;

    $account = \Hogart\Lk\Entity\AccountTable::getAccountByUserID($_SESSION["ACCOUNT_ID"]);
    $account['stores'] = \Hogart\Lk\Entity\AccountStoreRelationTable::getByAccountId($account['id']);
    $account['contacts'] = \Hogart\Lk\Entity\ContactRelationTable::getAccountContacts($account['id']);
    $account['managers'] = \Hogart\Lk\Entity\StaffRelationTable::getManagersByAccountId($account['id']);
    $account['is_general'] = \Hogart\Lk\Entity\AccountTable::isGeneralAccount($account['id']);

    $arResult['account'] = $account;

    if (defined("BX_COMP_MANAGED_CACHE"))
    {
        $CACHE_MANAGER->StartTagCache($this->getCachePath());
        $CACHE_MANAGER->RegisterTag("hogart_lk_account_" . $account['id']);
        $CACHE_MANAGER->EndTagCache();
    }
    
    $this->includeComponentTemplate();

    return $account['id'];
}