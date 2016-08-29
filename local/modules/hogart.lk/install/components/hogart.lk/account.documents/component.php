<?php
use Hogart\Lk\Entity\AccountTable;
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

if (!empty($_SESSION["ACCOUNT_ID"]) && !empty($_POST)) {
    switch ($_POST['action']){
        case 'change_company':
            $_SESSION['current_company_id'] = intval($_POST['cc_id']);
            LocalRedirect($APPLICATION->GetCurPage());
            die();
            break;

        case 'edit_company':
            $_SESSION['current_company_id'] = intval($_POST['cc_id']);
            break;
        case 'add-company-fiz':
            global $USER;
            // @TODO: проверить доступ
            $account = AccountTable::getAccountByUserID($USER->GetID());
            if($account) {
                $added_company_id = CompanyTable::add([
                    'name' => $_POST['last_name'] . ' ' . $_POST['name'] . ' ' . $_POST['middle_name'],
                    'type' => CompanyTable::TYPE_INDIVIDUAL,
                    'doc_pass' => intval($_POST['doc_pass']),
                    'doc_serial' => $_POST['doc_serial'],
                    'doc_number' => $_POST['doc_number'],
                    'doc_ufms' => $_POST['doc_ufms'],
                    'doc_date' => new Date($_POST['doc_date'], 'd.m.Y'),
                    'is_active' => true
                ]);
                AccountCompanyRelationTable::add([
                    'company_id'=>$added_company_id,
                    'account_id'=>$account['id']
                ]);

                foreach([AddressTypeTable::TYPE_ACTUAL, AddressTypeTable::TYPE_RESIDENTIAL] as $adrTypeCode){
                    $address_type = AddressTypeTable::getByField('code',$adrTypeCode);
                    $added_address_id = AddressTable::add([
                        'owner_id' => $added_company_id,
                        'owner_type' => AddressTable::OWNER_TYPE_CLIENT_COMPANY,
                        'type_id' => $address_type['id'],
                        'postal_code' => $_POST['postal_code'][$adrTypeCode],
                        'region' => $_POST['region'][$adrTypeCode],
                        'city' => $_POST['city'][$adrTypeCode],
                        'street' => $_POST['street'][$adrTypeCode],
                        'house' => $_POST['house'][$adrTypeCode],
                        'building' => $_POST['building'][$adrTypeCode],
                        'flat' => $_POST['flat'][$adrTypeCode],
                        'is_active' => true
                    ]);
                }

            }
//            LocalRedirect($APPLICATION->GetCurPage());
            die();
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

    $account = AccountTable::getAccountByUserID($USER->GetID());
    $arResult['account'] = $account;

    // Юридические лица (они же компании)
    $companies = AccountCompanyRelationTable::getByAccountId($account['id']);
    if(count($companies) == 1)
        $current_company = $companies[0];
    else{
        if(isset($_SESSION['cc_id']) && is_int($_SESSION['cc_id'])){
            $current_company = AccountCompanyRelationTable::getCurrentCompany(intval($_SESSION['cc_id']), $account['id']);
        }else{
            $current_company = AccountCompanyRelationTable::getCurrentCompany($companies[0]['COMPANY_id'], $account['id']);
        }
    }

    foreach($companies as $key=>$data){
        if($data['COMPANY_id'] == $current_company['COMPANY_id'])
            $companies[$key]['selected'] = 'selected';
    }

    $arResult['companies'] = $companies;
    $arResult['current_company'] = $current_company;
    var_dump($current_company);

    if (defined("BX_COMP_MANAGED_CACHE"))
    {
        $CACHE_MANAGER->StartTagCache($this->getCachePath());
        $CACHE_MANAGER->RegisterTag("hogart_lk_account_" . $account['id']);
        $CACHE_MANAGER->EndTagCache();
    }
    
    $this->includeComponentTemplate();

    return $account['id'];
}