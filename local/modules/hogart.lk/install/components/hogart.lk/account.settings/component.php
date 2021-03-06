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
if (!CModule::IncludeModule("hogart.lk")) {
    $this->abortResultCache();
    ShowError("Не установлен модуль \"Модуль личного кабинета компании Хогарт\"");
    return;
}

use Hogart\Lk\Helper\Template\FlashError;
use Hogart\Lk\Logger\BitrixLogger;
use Hogart\Lk\Entity\AccountTable;
use Hogart\Lk\Entity\AccountStoreRelationTable;
use Hogart\Lk\Entity\ContactRelationTable;
use Hogart\Lk\Entity\StaffRelationTable;

if (!$this->initComponentTemplate())
    return;

global $USER, $CACHE_MANAGER;
$account = \Hogart\Lk\Helper\Template\Account::getAccount();
$logger = (new BitrixLogger($this->getName()));

include (__DIR__ . "/proceed_request.php");

if ($account['id']) {
    $account['stores'] = AccountStoreRelationTable::getByAccountId($account['id']);
    $account_contact = AccountTable::getContact($account['id']);

    $account['contacts'] = array_reduce(ContactRelationTable::getAccountContacts($account['id']), function ($result, $item) {
        $result[$item['contact_id']] = $item;
        return $result;
    }, []);

    $account['contacts'] += [$account_contact['id'] => $account_contact];
    $account['managers'] = StaffRelationTable::getManagersByAccountId($account['id']);
    $account['sub_accounts'] = AccountTable::getSubAccounts($account['id']);
    $account['is_general'] = AccountTable::isGeneralAccount($account['id']);
    $arResult['account'] = $account;

    $this->includeComponentTemplate();
} else {
    new FlashError("У Вас нет доступа в данный раздел");
    LocalRedirect("/");
}