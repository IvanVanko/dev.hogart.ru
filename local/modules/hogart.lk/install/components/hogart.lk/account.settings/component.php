<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 15/08/16
 * Time: 16:16
 * 
 * @var $this \CBitrixComponent
 * @global CMain $APPLICATION
 * @global CUser $USER
 */
use Hogart\Lk\Logger\BitrixLogger;
use Hogart\Lk\Entity\AccountTable;
use Hogart\Lk\Entity\AccountStoreRelationTable;
use Hogart\Lk\Entity\ContactRelationTable;
use Hogart\Lk\Entity\StaffRelationTable;
use Hogart\Lk\Entity\StoreTable;

if (!$this->initComponentTemplate())
    return;

global $USER, $CACHE_MANAGER;
$account = AccountTable::getAccountByUserID($USER->GetID());
$logger = (new BitrixLogger($this->getName(), BitrixLogger::STACK_FULL));

include (__DIR__ . "/proceed_request.php");

if ($this->startResultCache()) {
    if (!CModule::IncludeModule("hogart.lk")) {
        $this->abortResultCache();
        ShowError("Не установлен модуль \"Модуль личного кабинета компании Хогарт\"");
        return;
    }

    $account['stores'] = AccountStoreRelationTable::getByAccountId($account['id']);
    $account['contacts'] = ContactRelationTable::getAccountContacts($account['id']);
    $account['managers'] = StaffRelationTable::getManagersByAccountId($account['id']);
    $account['sub_accounts'] = AccountTable::getSubAccounts($account['id']);
    $account['is_general'] = AccountTable::isGeneralAccount($account['id']);
    $arResult['account'] = $account;

    $arResult['stores'] = array_reduce(StoreTable::getList([
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