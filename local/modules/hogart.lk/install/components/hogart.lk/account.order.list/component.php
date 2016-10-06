<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 27/09/2016
 * Time: 01:10
 *
 * @global $APPLICATION
 */
if (!CModule::IncludeModule("hogart.lk")) {
    $this->abortResultCache();
    ShowError("Не установлен модуль \"Модуль личного кабинета компании Хогарт\"");
    return;
}

use Hogart\Lk\Helper\Template\FlashError;
use Bitrix\Main\Localization\Loc;
use Hogart\Lk\Entity\AccountTable;
use Hogart\Lk\Entity\OrderTable;

if (!$this->initComponentTemplate())
    return;

Loc::loadMessages(__FILE__);

global $USER, $CACHE_MANAGER;
$account = AccountTable::getAccountByUserID($USER->GetID());
$arParams['account'] = $account;

$states = [
    'active' => OrderTable::STATE_NORMAL,
    'draft' => OrderTable::STATE_DRAFT,
    'archive' => OrderTable::STATE_ARCHIVE
];

if ($account['id']) {

    include __DIR__ . "/proceed_filter.php";

    $state = $states[$arParams['STATE']];

    if (!empty($_REQUEST['copy_to_cart'])) {
        OrderTable::copyToCart($_REQUEST['copy_to_cart']);
        LocalRedirect('/account/cart/');
    }

    if (!empty($_REQUEST['copy_to_draft'])) {
        OrderTable::copyToDraft($_REQUEST['copy_to_draft']);
        LocalRedirect('/account/orders/');
    }

    if (!empty($_REQUEST['delete'])) {
        OrderTable::delete($_REQUEST['delete']);
        LocalRedirect($APPLICATION->GetCurPage(false));
    }

    $APPLICATION->AddChainItem(Loc::getMessage($arParams['STATE']));

    $nav = new \Bitrix\Main\UI\PageNavigation("nav-more-orders");
    $nav->allowAllRecords(false)
        ->setPageSize(20)
        ->initFromUri();

    $arParams['nav'] = $nav;

    $arResult['orders'] = OrderTable::getByAccount($account['id'], $nav, $state, $arParams['filter']);
    $arResult['companies'] = OrderTable::getCompaniesByAccount($account['id'], $state, $arParams['filter']);
    $arResult['stores'] = OrderTable::getStoresByAccount($account['id'], $state, $arParams['filter']);
    $arParams['STATE'] = $state;

    $this->includeComponentTemplate();
} else {
    new FlashError("У Вас нет доступа в данный раздел");
    LocalRedirect("/");
}