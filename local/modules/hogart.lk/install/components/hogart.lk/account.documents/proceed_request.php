<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 06/09/16
 * Time: 22:11
 *
 * @global CMain $APPLICATION
 * @global CUser $USER
 */

use Hogart\Lk\Entity\AccountTable;
use Hogart\Lk\Entity\ContactRelationTable;
use Hogart\Lk\Entity\PaymentAccountTable;
use Hogart\Lk\Entity\PaymentAccountRelationTable;
use Hogart\Lk\Entity\ContactInfoTable;
use Hogart\Lk\Entity\ContactTable;
use Hogart\Lk\Entity\AccountCompanyRelationTable;
use Hogart\Lk\Entity\AddressTypeTable;
use Hogart\Lk\Entity\AddressTable;
use Hogart\Lk\Entity\CompanyTable;
use Hogart\Lk\Entity\ContractTable;
use Bitrix\Main\Type\Date;
use Hogart\Lk\Helper\Template\FlashError;
use Hogart\Lk\Helper\Template\FlashSuccess;
use Hogart\Lk\Helper\Mail\Event;

global $APPLICATION, $DB;

if (!empty($account['id']) && !empty($_REQUEST)) {

    switch ($_REQUEST['action']) {
        case 'change_company':
            $_SESSION['current_company_id'] = intval($_POST['cc_id']);
            LocalRedirect($APPLICATION->GetCurPage(false));
            die();
            break;
        case 'edit_company':
            die();
            break;
        case 'add-address':
            $address = json_decode($_POST['__address']);
            $address_type = AddressTypeTable::getByField('code', AddressTypeTable::TYPE_DELIVERY);
            $addressRes = AddressTable::replace([
                'owner_id' => $_SESSION['current_company_id'],
                'owner_type' => AddressTable::OWNER_TYPE_CLIENT_COMPANY,
                'type_id' => $address_type['id'],
                'postal_code' => $address->data->postal_code,
                'region' => $address->data->region_with_type,
                'city' => $address->data->city_with_type,
                'street' => $address->data->street_with_type,
                'house' => "" . $address->data->house,
                'building' => "" . $address->block,
                'flat' => "" . $address->data->flat,
                'value' => $address->unrestricted_value,
                'fias_code' => $address->data->fias_id,
                'kladr_code' => $address->data->kladr_id,
                'is_active' => (bool)$_POST['is_active']
            ]);
            LocalRedirect($APPLICATION->GetCurPage(false));
            die();
            break;
        case 'add-contact':
            $result = ContactTable::createOrUpdateByField([
                'name' => $_POST['name'],
                'last_name' => $_POST['last_name'],
                'middle_name' => $_POST['middle_name'],
                'is_active' => true
            ], 'hash');
            if (($contact_id =$result->getId())) {
                ContactRelationTable::replace([
                    'contact_id' => $contact_id,
                    'owner_id' => $_SESSION['current_company_id'],
                    'owner_type' => ContactRelationTable::OWNER_TYPE_CLIENT_COMPANY
                ]);
                if ($_POST['email']) {
                    $ciR = ContactInfoTable::replace([
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
                        $ciR = ContactInfoTable::replace([
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
            LocalRedirect($APPLICATION->GetCurPage(false));
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
                    'owner_id' => $_SESSION['current_company_id'],
                    'owner_type' => ContactRelationTable::OWNER_TYPE_CLIENT_COMPANY
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
            LocalRedirect($APPLICATION->GetCurPage(false));
            die();
            break;
        case 'add-company':
            $DB->StartTransaction();
            $new_company = [
                'type' => intval($_POST['company_type']),
                'is_active' => (bool)$_POST['is_active']
            ];

            switch (intval($_POST['company_type'])) {
                case 1:
                    $chiefResult = ContactTable::createOrUpdateByField([
                        'name' => $_POST['director_name'],
                        'last_name' => $_POST['director_last_name'],
                        'middle_name' => $_POST['director_middle_name'],
                    ], 'hash');
                    $nameData = json_decode($_POST['__name']);
                    $new_company = array_merge($new_company, [
                        'name' => $_POST['name'],
                        'inn' => $_POST['inn'],
                        'kpp' => $_POST['kpp'],
                        'chief_contact_id' => $chiefResult->getId()
                    ]);
                    break;
                case 2:
                    $new_company = array_merge($new_company, [
                        'name' => $_POST['name'],
                        'inn' => $_POST['inn'],
                        'doc_pass' => intval($_POST['doc_pass']),
                        'doc_serial' => $_POST['doc_serial'],
                        'doc_number' => $_POST['doc_number'],
                        'doc_ufms' => $_POST['doc_ufms'],
                        'doc_date' => !empty($_POST['doc_date']) ? new Date($_POST['doc_date'], 'd.m.Y') : "",
                    ]);
                    break;
                case 3:
                    $new_company = array_merge($new_company, [
                        'name' => $_POST['last_name'] . ' ' . $_POST['name'] . ' ' . $_POST['middle_name'],
                        'date_fact_address' => new Date($_POST['date_fact_address'], 'd.m.Y'),
                        'doc_pass' => intval($_POST['doc_pass']),
                        'doc_serial' => $_POST['doc_serial'],
                        'doc_number' => $_POST['doc_number'],
                        'doc_ufms' => $_POST['doc_ufms'],
                        'doc_date' => !empty($_POST['doc_date']) ? new Date($_POST['doc_date'], 'd.m.Y') : "",
                    ]);
                    break;
            }

            $added_company_result = CompanyTable::createOrUpdateByField($new_company, 'hash');
            if ($added_company_result->getId()) {
                AccountCompanyRelationTable::replace([
                    'company_id' => $added_company_result->getId(),
                    'account_id' => $account['id']
                ]);

                if (!empty($_POST['residential_address_as_actual']) || !empty($_POST['actual_address_as_legal'])) {
                    $_POST['__address'][AddressTypeTable::TYPE_ACTUAL] = $_POST['__address'][AddressTypeTable::TYPE_LEGAL];
                }

                foreach([AddressTypeTable::TYPE_ACTUAL, AddressTypeTable::TYPE_LEGAL] as $adrTypeCode){
                    $address_type = AddressTypeTable::getByField('code',$adrTypeCode);
                    if (!empty($_POST['__address'][$adrTypeCode])) {
                        $address = json_decode($_POST['__address'][$adrTypeCode]);
                        $addressRes = AddressTable::replace([
                            'owner_id' => $added_company_result->getId(),
                            'owner_type' => AddressTable::OWNER_TYPE_CLIENT_COMPANY,
                            'type_id' => $address_type['id'],
                            'postal_code' => $address->data->postal_code,
                            'region' => $address->data->region_with_type,
                            'city' => $address->data->city_with_type,
                            'street' => $address->data->street_with_type,
                            'house' => "" . $address->data->house,
                            'building' => "" . $address->block,
                            'flat' => "" . $address->data->flat,
                            'value' => $address->unrestricted_value,
                            'fias_code' => $address->data->fias_id,
                            'kladr_code' => $address->data->kladr_id,
                            'is_active' => true
                        ]);
                    }
                }

                if (!empty($_POST['email'])) {

                    if (!is_array($_POST['email']))
                        $_POST['email'] = [$_POST['email']];

                    foreach ($_POST['email'] as $email) {
                        ContactInfoTable::add([
                            'owner_id' => $added_company_result->getId(),
                            'owner_type' => \Hogart\Lk\Entity\ContactInfoTable::OWNER_TYPE_CLIENT_COMPANY,
                            'info_type' => \Hogart\Lk\Entity\ContactInfoTable::TYPE_EMAIL,
                            'value' => $email,
                            'is_active' => true
                        ]);
                    }
                }
                if (!empty($_POST['phone'][ContactInfoTable::PHONE_KIND_STATIC])) {

                    if (!is_array($_POST['phone'][ContactInfoTable::PHONE_KIND_STATIC]))
                        $_POST['phone'][ContactInfoTable::PHONE_KIND_STATIC] = [$_POST['phone'][ContactInfoTable::PHONE_KIND_STATIC]];

                    foreach ($_POST['phone'][ContactInfoTable::PHONE_KIND_STATIC] as $phone) {
                        ContactInfoTable::add([
                            'owner_id' => $added_company_result->getId(),
                            'owner_type' => \Hogart\Lk\Entity\ContactInfoTable::OWNER_TYPE_CLIENT_COMPANY,
                            'info_type' => \Hogart\Lk\Entity\ContactInfoTable::TYPE_PHONE,
                            'phone_kind' => \Hogart\Lk\Entity\ContactInfoTable::PHONE_KIND_STATIC,
                            'value' => $phone,
                            'is_active' => true
                        ]);
                    }
                }

                switch (intval($_POST['company_type'])) {
                    case 1:
                    case 2:

                        if (!is_array($_POST['payment_account']['number'])) {
                            $_POST['payment_account']['number'] = [$_POST['payment_account']['number']];
                            $_POST['payment_account']['is_main'] = [$_POST['payment_account']['is_main']];
                            $_POST['__payment_account']['bik'] = [$_POST['__payment_account']['bik']];
                        }

                        foreach ($_POST['payment_account']['number'] as $index => $payment_number) {
                            $bikData = json_decode($_POST['__payment_account']['bik'][$index]);
                            $paymentRes = PaymentAccountTable::createOrUpdateByField([
                                'number' => $payment_number,
                                'bik' => $bikData->data->bic,
                                'bank_name' => $bikData->data->name->payment,
                                'corr_number' => $bikData->data->correspondent_account,
                                'is_active' => true
                            ], 'hash');
                            if ($paymentRes->getId()) {
                                PaymentAccountRelationTable::replace([
                                    'payment_account_id' => $paymentRes->getId(),
                                    'owner_id' => $added_company_result->getId(),
                                    'owner_type' => PaymentAccountRelationTable::OWNER_TYPE_CLIENT_COMPANY,
                                    'is_main' => (bool)$_POST['payment_account']['is_main'][$index]
                                ]);
                            }
                        }
                        break;
                }

                $start_date = new DateTime();
                $end_date = new DateTime();
                $end_date->setDate($start_date->format('Y'), '12', '31');
                $diff = $start_date->diff(new DateTime($start_date->format('Y') . "-10-01"));
                if ($diff->format('%R%a') < 0) {
                    $end_date->add(new DateInterval('P1Y'));
                }

                $company = $added_company_result->getData();
                $contract = [
                    'company_id' => $added_company_result->getId(),
                    'hogart_company_id' => "",
                    'start_date' => Date::createFromPhp($start_date),
                    'end_date' => Date::createFromPhp($end_date),
                    'currency_code' => "RUB",
                    'perm_item' => true,
                    'perm_promo' => false,
                    'perm_clearing' => true,
                    'perm_card' => $company['type'] != CompanyTable::TYPE_LEGAL_ENTITY,
                    'perm_cash' => true,
                    'is_active' => true
                ];

                ContractTable::add($contract);

                $DB->Commit();
                new FlashSuccess(vsprintf("Создана компания %s", [$added_company_result->getData()['name']]));
            } else {
                $DB->Rollback();
            }
            LocalRedirect($APPLICATION->GetCurPage(false));
            die();
            break;
        case 'edit-company':
            $company = CompanyTable::getRowById((int)$_REQUEST['id']);
            
            if (empty($company['id']) || !AccountCompanyRelationTable::isHaveAccess($account['id'], $company['id'])) {
                new FlashError("У Вас нет доступа до управления компанией");
                break;
            }

            $new_company = [];

            switch (intval($company['type'])) {
                case 1:
                    $chiefResult = ContactTable::update($company['chief_contact_id'], [
                        'name' => $_POST['director_name'],
                        'last_name' => $_POST['director_last_name'],
                        'middle_name' => $_POST['director_middle_name'],
                    ]);
                    break;
                case 2:
                    $new_company = array_merge($new_company, [
                        'doc_pass' => intval($_POST['doc_pass']),
                        'doc_serial' => $_POST['doc_serial'],
                        'doc_number' => $_POST['doc_number'],
                        'doc_ufms' => $_POST['doc_ufms'],
                        'doc_date' => !empty($_POST['doc_date']) ? new Date($_POST['doc_date'], 'd.m.Y') : "",
                    ]);
                    break;
                case 3:
                    $new_company = array_merge($new_company, [
                        'date_fact_address' => new Date($_POST['date_fact_address'], 'd.m.Y'),
                        'doc_pass' => intval($_POST['doc_pass']),
                        'doc_serial' => $_POST['doc_serial'],
                        'doc_number' => $_POST['doc_number'],
                        'doc_ufms' => $_POST['doc_ufms'],
                        'doc_date' => !empty($_POST['doc_date']) ? new Date($_POST['doc_date'], 'd.m.Y') : "",
                    ]);
                    break;
            }

            if (!empty($new_company))
                $company_result = CompanyTable::update($company['id'], $new_company);
            if (!empty($company['id'])) {
                if (!empty($_POST['residential_address_as_actual']) || !empty($_POST['actual_address_as_legal'])) {
                    $_POST['__address'][AddressTypeTable::TYPE_ACTUAL] = $_POST['__address'][AddressTypeTable::TYPE_LEGAL];
                }

                foreach([AddressTypeTable::TYPE_ACTUAL, AddressTypeTable::TYPE_LEGAL] as $adrTypeCode){
                    $address_type = AddressTypeTable::getByField('code',$adrTypeCode);
                    if (!empty($_POST['__address'][$adrTypeCode])) {
                        $address = json_decode($_POST['__address'][$adrTypeCode]);
                        $addressRes = AddressTable::replace([
                            'owner_id' => $company['id'],
                            'owner_type' => AddressTable::OWNER_TYPE_CLIENT_COMPANY,
                            'type_id' => $address_type['id'],
                            'postal_code' => $address->data->postal_code,
                            'region' => $address->data->region_with_type,
                            'city' => $address->data->city_with_type,
                            'street' => $address->data->street_with_type,
                            'house' => "" . $address->data->house,
                            'building' => "" . $address->block,
                            'flat' => "" . $address->data->flat,
                            'value' => $address->unrestricted_value,
                            'fias_code' => $address->data->fias_id,
                            'kladr_code' => $address->data->kladr_id,
                            'is_active' => true
                        ]);
                    }
                }

                $payment_accounts = PaymentAccountRelationTable::getByOwner(
                    $company['id'],
                    PaymentAccountRelationTable::OWNER_TYPE_CLIENT_COMPANY
                );
                foreach ($payment_accounts as $payment_account) {
                    PaymentAccountTable::update($payment_account['payment_account_id'], [
                        'is_active' => false
                    ]);
                }

                switch (intval($company['type'])) {
                    case 1:
                    case 2:

                        if (!is_array($_POST['payment_account']['number'])) {
                            $_POST['payment_account']['number'] = [$_POST['payment_account']['number']];
                            $_POST['payment_account']['is_main'] = [$_POST['payment_account']['is_main']];
                            $_POST['__payment_account']['bik'] = [$_POST['__payment_account']['bik']];
                        }

                        foreach ($_POST['payment_account']['number'] as $index => $payment_number) {
                            $bikData = json_decode($_POST['__payment_account']['bik'][$index]);
                            $paymentRes = PaymentAccountTable::createOrUpdateByField([
                                'number' => $payment_number,
                                'bik' => $bikData->data->bic,
                                'bank_name' => $bikData->data->name->payment,
                                'corr_number' => $bikData->data->correspondent_account,
                                'is_active' => true
                            ], 'hash');
                            if ($paymentRes->getId()) {
                                PaymentAccountRelationTable::replace([
                                    'payment_account_id' => $paymentRes->getId(),
                                    'owner_id' => $company['id'],
                                    'owner_type' => PaymentAccountRelationTable::OWNER_TYPE_CLIENT_COMPANY,
                                    'is_main' => (bool)$_POST['payment_account']['is_main'][$index]
                                ]);
                            }
                        }
                        break;
                }

            }
            LocalRedirect($APPLICATION->GetCurPage(false));
            die();
            break;
    }
    if (!empty($_REQUEST['fav_company'])) {
        AccountCompanyRelationTable::toggleFavorite($account['id'], $_REQUEST['fav_company']);
    }
    if (!empty($_REQUEST['fav_contract'])) {
        AccountTable::update($account['id'], [
            "main_contract_id" => intval($_REQUEST['fav_contract'])
        ]);
        $account = AccountTable::getAccountByUserID($USER->GetID());
    }
    if (!empty($_REQUEST['remove_company'])) {
        if (AccountCompanyRelationTable::isHaveAccess($account['id'], $_REQUEST['remove_company'], true)) {
            if (AccountTable::isGeneralAccount($account['id'])) {
                CompanyTable::update($_REQUEST['remove_company'], [
                    "is_active" => false,
                ]);
                new \Hogart\Lk\Helper\Template\FlashSuccess("Компания удалена");
            } else {
                new \Hogart\Lk\Helper\Template\FlashError("У Вас нет доступа до управления компанией");
            }
        }
    }
    if (!empty($_REQUEST['remove_contact'])) {
        ContactRelationTable::delete([
            "contact_id" => $_REQUEST['remove_contact'],
            "owner_id" => $_SESSION['current_company_id'],
            "owner_type" => ContactRelationTable::OWNER_TYPE_CLIENT_COMPANY
        ]);
    }
    if (!empty($_REQUEST['remove_address'])) {
        $address_type = AddressTypeTable::getByField('code', AddressTypeTable::TYPE_DELIVERY);
        AddressTable::delete([
            "type_id" => $address_type["id"],
            "owner_id" => $_SESSION['current_company_id'],
            "owner_type" => ContactRelationTable::OWNER_TYPE_CLIENT_COMPANY,
            "fias_code" => $_REQUEST['remove_address']
        ]);
    }

    if (!empty($_REQUEST['get_docs'])) {
        $contract = ContractTable::getRowById(intval($_REQUEST['get_docs']));
        if (Event::CompanyDocRequest(intval($_REQUEST['get_docs']))) {
            new FlashSuccess(vsprintf("Запрос на получение оригиналов по договору <u><b>%s</b></u> отправлен", [ContractTable::showName($contract)]));
        }
    }
}