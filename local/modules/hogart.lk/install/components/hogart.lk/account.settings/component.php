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

if ($this->startResultCache()) {
    if (!CModule::IncludeModule("hogart.lk")) {
        $this->abortResultCache();
        ShowError("Не установлен модуль \"Модуль личного кабинета компании Хогарт\"");
        return;
    }
    global $CACHE_MANAGER;

    $account = \Hogart\Lk\Entity\AccountTable::getAccountByUserID($USER->GetID());
    $account['stores'] = \Hogart\Lk\Entity\AccountStoreRelationTable::getByAccountId($account['id']);
    $account['contacts'] = \Hogart\Lk\Entity\ContactRelationTable::getAccountContacts($account['id']);

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