<?php
use Hogart\Lk\Entity\AccountTable;
use Hogart\Lk\Entity\AccountCompanyRelationTable;
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

if ($this->startResultCache()) {
    if (!CModule::IncludeModule("hogart.lk")) {
        $this->abortResultCache();
        ShowError("Не установлен модуль \"Модуль личного кабинета компании Хогарт\"");
        return;
    }
    global $CACHE_MANAGER;

    $account = AccountTable::getAccountByUserID($USER->GetID());

    $account['companies'] = AccountCompanyRelationTable::getByAccountId($account['id']);
    if(count($account['companies']) == 1)
        $account['current_company'] = $account['companies'][0];
    else
        // @TODO: сделать выборку текущей компании из селекта
        $account['current_company'] = AccountCompanyRelationTable::getCurrentCompany($account['company'][0]['COMPANY_id'], $account['id']);

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