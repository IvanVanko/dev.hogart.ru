<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 06/09/16
 * Time: 22:30
 * 
 * @global CMain $APPLICATION
 * @global CUser $USER
 */

use Bitrix\Main\Web\Json;
use Hogart\Lk\Exchange\RabbitMQ\Consumer;
use Hogart\Lk\Exchange\RabbitMQ\Exchange\AccountExchange;
use Hogart\Lk\Entity\AccountTable;
use Hogart\Lk\Entity\AccountStoreRelationTable;
use Hogart\Lk\Entity\ContactTable;
use Hogart\Lk\Entity\ContactInfoTable;
use Hogart\Lk\Entity\ContactRelationTable;

if (!empty($account['id']) && !empty($_REQUEST)) {
    switch ($_REQUEST['action']) {
        case 'change-password':
            global $USER;
            $APPLICATION->RestartBuffer();
            $email = $USER->GetEmail();
            try {
                $accountExchange = (new AccountExchange())->useConsumer(Consumer::getInstance());
                $accountExchange->getExchange()->publish($email, $accountExchange->getPublishKey('send_password'), AMQP_NOPARAM, ['delivery_mode' => 2]);
                echo Json::encode([]);
            } catch (Exception $e) {
                echo Json::encode([
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
                AccountStoreRelationTable::replace([
                    "account_id" => $account['id'],
                    "store_guid" => $store_guid
                ]);
            }
            LocalRedirect($APPLICATION->GetCurPage());
            break;
        case 'add-contact':
            $result = ContactTable::createOrUpdateByField([
                'name' => $_POST['name'],
                'last_name' => $_POST['last_name'],
                'middle_name' => $_POST['middle_name'],
                'is_active' => true
            ], 'hash');
            if (($contact_id =$result->getId())) {
                ContactRelationTable::add([
                    'contact_id' => $contact_id,
                    'owner_id' => $account['id'],
                    'owner_type' => ContactRelationTable::OWNER_TYPE_ACCOUNT
                ]);
                if ($_POST['email']) {
                    $ciR = ContactInfoTable::add([
                        'owner_id' => $contact_id,
                        'owner_type' => ContactInfoTable::OWNER_TYPE_CONTACT,
                        'info_type' => ContactInfoTable::TYPE_EMAIL,
                        'value' => $_POST['email'],
                        'is_active' => true
                    ]);
                }
                if ($_POST['phone']) {
                    foreach ($_POST['phone'] as $kind => $phone) {
                        if (empty($phone)) continue;
                        $ciR = ContactInfoTable::add([
                            'owner_id' => $contact_id,
                            'owner_type' => ContactInfoTable::OWNER_TYPE_CONTACT,
                            'info_type' => ContactInfoTable::TYPE_PHONE,
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
            $result = ContactTable::update($_POST['id'], [
                'name' => $_POST['name'],
                'last_name' => $_POST['last_name'],
                'middle_name' => $_POST['middle_name'],
                'is_active' => true
            ]);
            if (($contact_id =$result->getId())) {
                ContactRelationTable::replace([
                    'contact_id' => $contact_id,
                    'owner_id' => $account['id'],
                    'owner_type' => ContactRelationTable::OWNER_TYPE_ACCOUNT
                ]);
                ContactInfoTable::delete([
                    'owner_id' => $contact_id,
                    'owner_type' => ContactInfoTable::OWNER_TYPE_CONTACT,
                    'info_type' => ContactInfoTable::TYPE_EMAIL
                ]);

                ContactInfoTable::delete([
                    'owner_id' => $contact_id,
                    'owner_type' => ContactInfoTable::OWNER_TYPE_CONTACT,
                    'info_type' => ContactInfoTable::TYPE_PHONE,
                    'phone_kind' => ContactInfoTable::PHONE_KIND_MOBILE
                ]);
                ContactInfoTable::delete([
                    'owner_id' => $contact_id,
                    'owner_type' => ContactInfoTable::OWNER_TYPE_CONTACT,
                    'info_type' => ContactInfoTable::TYPE_PHONE,
                    'phone_kind' => ContactInfoTable::PHONE_KIND_STATIC
                ]);

                if ($_POST['email']) {
                    $ciR = ContactInfoTable::add([
                        'owner_id' => $contact_id,
                        'owner_type' => ContactInfoTable::OWNER_TYPE_CONTACT,
                        'info_type' => ContactInfoTable::TYPE_EMAIL,
                        'value' => $_POST['email'],
                        'is_active' => true
                    ]);
                }
                if ($_POST['phone']) {
                    foreach ($_POST['phone'] as $kind => $phone) {
                        if (empty($phone)) continue;
                        $ciR = ContactInfoTable::add([
                            'owner_id' => $contact_id,
                            'owner_type' => ContactInfoTable::OWNER_TYPE_CONTACT,
                            'info_type' => ContactInfoTable::TYPE_PHONE,
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
        AccountTable::update($account['id'], [
            "main_store_id" => $_REQUEST['fav_store']
        ]);
    }
    if (!empty($_REQUEST['remove_store'])) {
        AccountStoreRelationTable::delete([
            "account_id" => $account['id'],
            "store_guid" => $_REQUEST['remove_store']
        ]);
    }
    if (!empty($_REQUEST['remove_contact'])) {
        ContactRelationTable::delete([
            "contact_id" => $_REQUEST['remove_contact'],
            "owner_id" => $account['id'],
            "owner_type" => ContactRelationTable::OWNER_TYPE_ACCOUNT
        ]);
    }

    // Постановка задачи на отправку аккаунта в КИС
    try {
        $accountExchange = (new AccountExchange())->useConsumer(Consumer::getInstance());
        $accountExchange->getExchange()->publish("", $accountExchange->getPublishKey('set'), AMQP_NOPARAM, ['delivery_mode' => 2]);
    } catch (Exception $e) {
        $logger->error(get_class($e) . ": ". $e->getMessage());
    }
}