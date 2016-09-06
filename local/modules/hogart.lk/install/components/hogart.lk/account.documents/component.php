<?php

use Hogart\Lk\Entity\AccountTable;
use Hogart\Lk\Entity\ContactRelationTable;
use Hogart\Lk\Entity\ContractTable;
use Hogart\Lk\Entity\PaymentAccountTable;
use Hogart\Lk\Entity\PaymentAccountRelationTable;
use Hogart\Lk\Entity\ContactInfoTable;
use Hogart\Lk\Entity\ContactTable;
use Hogart\Lk\Entity\AccountCompanyRelationTable;
use Hogart\Lk\Entity\AddressTypeTable;
use Hogart\Lk\Entity\AddressTable;
use Hogart\Lk\Entity\CompanyTable;
use Bitrix\Main\Type\Date;
/**
 * Компонент отображения Юридических лиц
 * By: Ivan Kiselev aka shaqito[at]gmail.com
 * Via: PhpStorm.
 * At: 18.08.2016 16:27
 *
 * @var $this CBitrixComponent
 * @var $USER CUser
 */

if (!$this->initComponentTemplate())
    return;

global $APPLICATION, $USER;
if (!empty($_SESSION["ACCOUNT_ID"]) && !empty($_REQUEST)) {

    switch ($_REQUEST['action']) {
        case 'change_company':
            $_SESSION['current_company_id'] = intval($_POST['cc_id']);
            LocalRedirect($APPLICATION->GetCurPage());
            die();
            break;

        case 'edit_company':
            $_SESSION['current_company_id'] = intval($_POST['cc_id']);
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
            LocalRedirect($APPLICATION->GetCurPage());
            break;
        case 'add-company':
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
                        'doc_date' => !empty($_POST['doc_date']) ? new Date($_POST['doc_date'], 'd/m/Y') : "",
                    ]);
                    break;
                case 3:
                    $new_company = array_merge($new_company, [
                        'name' => $_POST['last_name'] . ' ' . $_POST['name'] . ' ' . $_POST['middle_name'],
                        'date_fact_address' => new Date($_POST['date_fact_address'], 'd/m/Y'),
                        'doc_pass' => intval($_POST['doc_pass']),
                        'doc_serial' => $_POST['doc_serial'],
                        'doc_number' => $_POST['doc_number'],
                        'doc_ufms' => $_POST['doc_ufms'],
                        'doc_date' => !empty($_POST['doc_date']) ? new Date($_POST['doc_date'], 'd/m/Y') : "",
                    ]);
                    break;
            }

            $added_company_result = CompanyTable::createOrUpdateByField($new_company, 'hash');
            if ($added_company_result->getId()) {
                AccountCompanyRelationTable::replace([
                    'company_id' => $added_company_result->getId(),
                    'account_id' => $_SESSION["ACCOUNT_ID"]
                ]);

                if (!empty($_POST['residential_address_as_actual'])) {
                    $_POST['__address'][AddressTypeTable::TYPE_RESIDENTIAL] = $_POST['__address'][AddressTypeTable::TYPE_ACTUAL];
                }

                if (!empty($_POST['actual_address_as_legal'])) {
                    $_POST['__address'][AddressTypeTable::TYPE_ACTUAL] = $_POST['__address'][AddressTypeTable::TYPE_LEGAL];
                }

                foreach([AddressTypeTable::TYPE_ACTUAL, AddressTypeTable::TYPE_RESIDENTIAL, AddressTypeTable::TYPE_LEGAL] as $adrTypeCode){
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

            }
            LocalRedirect($APPLICATION->GetCurPage());
            die();
            break;
    }
    if (!empty($_REQUEST['fav_company'])) {
        AccountCompanyRelationTable::toggleFavorite($_SESSION["ACCOUNT_ID"], $_REQUEST['fav_company']);
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
}
if ($this->startResultCache()) {
    if (!CModule::IncludeModule("hogart.lk")) {
        $this->abortResultCache();
        ShowError("Не установлен модуль \"Модуль личного кабинета компании Хогарт\"");
        return;
    }
    global $CACHE_MANAGER;

    // Юридические лица (они же компании)
    $account = AccountTable::getAccountById($_SESSION["ACCOUNT_ID"]);
    $account['is_general'] = AccountTable::isGeneralAccount($account['id']);
    $companies = AccountCompanyRelationTable::getByAccountId($_SESSION["ACCOUNT_ID"]);
    if(count($companies) == 1)
        $current_company = &$companies[0];
    else{
        $current_company = &$companies[$_SESSION['current_company_id'] ? : $companies[0]['id']];
    }

    foreach($companies as $key=> &$company){
        if($company['id'] == $current_company['id'])
            $company['selected'] = 'selected';

        $company['contracts'] = ContractTable::getByCompanyId($company['id']);
        $company['contacts'] = ContactRelationTable::getContactsByOwner($company['id'], ContactRelationTable::OWNER_TYPE_CLIENT_COMPANY);
        $company['addresses'] = AddressTable::getByOwner($company['id'], AddressTable::OWNER_TYPE_CLIENT_COMPANY);
    }

    $arResult['account'] = $account;
    $arResult['companies'] = $companies;
    $arResult['current_company'] = $current_company;

    if (defined("BX_COMP_MANAGED_CACHE"))
    {
        $CACHE_MANAGER->StartTagCache($this->getCachePath());
        $CACHE_MANAGER->RegisterTag("hogart_lk_account_" . $_SESSION["ACCOUNT_ID"]);
        $CACHE_MANAGER->EndTagCache();
    }
    
    $this->includeComponentTemplate();

    return $_SESSION["ACCOUNT_ID"];
}