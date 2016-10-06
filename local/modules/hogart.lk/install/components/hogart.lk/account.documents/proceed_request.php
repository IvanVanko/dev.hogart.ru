<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 06/09/16
 * Time: 22:11
 */
use Hogart\Lk\Entity\ContactRelationTable;
use Hogart\Lk\Entity\PaymentAccountTable;
use Hogart\Lk\Entity\PaymentAccountRelationTable;
use Hogart\Lk\Entity\ContactInfoTable;
use Hogart\Lk\Entity\ContactTable;
use Hogart\Lk\Entity\AccountCompanyRelationTable;
use Hogart\Lk\Entity\AddressTypeTable;
use Hogart\Lk\Entity\AddressTable;
use Hogart\Lk\Entity\CompanyTable;
use Bitrix\Main\Type\Date;

global $APPLICATION;

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
        case 'add-contract':
            $start_date = new DateTime();
            $end_date = new DateTime();
            $end_date->setDate($start_date->format('Y'), '12', '31');
            $diff = $start_date->diff(new DateTime($start_date->format('Y') . "-10-01"));
            if ($diff->format('%R%a') < 0) {
                $end_date->add(new DateInterval('P1Y'));
            }
            $contract = [
                'company_id' => $_SESSION['current_company_id'],
                'hogart_company_id' => (string)$_POST['hogart_company'],
                'start_date' => Date::createFromPhp($start_date),
                'end_date' => Date::createFromPhp($end_date),
                'currency_code' => $_POST['currency'],
                'perm_item' => (bool)$_POST['perm_item'],
                'perm_promo' => (bool)$_POST['perm_promo'],
                'perm_clearing' => (bool)$_POST['perm_clearing'],
                'perm_card' => (bool)$_POST['perm_card'],
                'perm_cash' => (bool)$_POST['perm_cash'],
                'is_active' => true
            ];

            \Hogart\Lk\Entity\ContractTable::add($contract);
            LocalRedirect($APPLICATION->GetCurPage(false));
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
                    'account_id' => $account['id']
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

            }
            LocalRedirect($APPLICATION->GetCurPage(false));
            die();
            break;
    }
    if (!empty($_REQUEST['fav_company'])) {
        AccountCompanyRelationTable::toggleFavorite($account['id'], $_REQUEST['fav_company']);
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