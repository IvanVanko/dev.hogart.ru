<?php
/**
 * Компонент отображения Юридических лиц
 * By: Ivan Kiselev aka shaqito[at]gmail.com
 * Via: PhpStorm.
 * At: 18.08.2016 16:27
 *
 * @var $this CBitrixComponent
 * @var $USER CUser
 * @global CMain $APPLICATION
 */

use Hogart\Lk\Entity\AccountTable;
use Hogart\Lk\Entity\ContactRelationTable;
use Hogart\Lk\Entity\ContractTable;
use Hogart\Lk\Entity\AccountCompanyRelationTable;
use Hogart\Lk\Entity\AddressTable;
use Hogart\Lk\Entity\HogartCompanyTable;


if (!$this->initComponentTemplate())
    return;

global $USER, $APPLICATION, $CACHE_MANAGER;
$account = AccountTable::getAccountByUserID($USER->GetID());

include (__DIR__ . "/proceed_request.php");

if ($this->startResultCache()) {
    if (!CModule::IncludeModule("hogart.lk")) {
        $this->abortResultCache();
        ShowError("Не установлен модуль \"Модуль личного кабинета компании Хогарт\"");
        return;
    }

    // Юридические лица (они же компании)
    $account = AccountTable::getAccountById($account['id']);
    $account['is_general'] = AccountTable::isGeneralAccount($account['id']);
    $companies = AccountCompanyRelationTable::getByAccountId($account['id']);
    if (count($companies) == 1) {
        $current_company = &$companies[0];
    } else {
        if (empty($_SESSION['current_company_id'])) {
            foreach ($companies as &$company) {
                if ($company['is_favorite']) {
                    $current_company = &$company;
                    break;
                }
            }
            if (empty($current_company)) {
                $current_company = &reset($companies);
            }
        } else {
            $current_company = &$companies[$_SESSION['current_company_id']];
        }
    }

    $_SESSION['current_company_id'] = $current_company['id'];

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
    $arResult['hogart_companies'] = HogartCompanyTable::getList([
        'filter' => [
            '=is_active' => true
        ]
    ])->fetchAll();

    $arResult['currency'] = \Bitrix\Currency\CurrencyTable::getList([
        'select' => [
            '*',
            'LANG_' => 'CURRENT_LANG_FORMAT'
        ]
    ])->fetchAll();

    if (defined("BX_COMP_MANAGED_CACHE"))
    {
        $CACHE_MANAGER->StartTagCache($this->getCachePath());
        $CACHE_MANAGER->RegisterTag("hogart_lk_account_" . $account['id']);
        $CACHE_MANAGER->EndTagCache();
    }
    
    $this->includeComponentTemplate();

    return $account['id'];
}