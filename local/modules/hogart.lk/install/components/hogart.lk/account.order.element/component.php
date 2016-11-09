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
if (!$this->initComponentTemplate())
    return;

define("NO_SPECIAL_CHARS_CHAIN", true);

use Bitrix\Main\Localization\Loc;
use Hogart\Lk\Entity\AccountTable;
use Hogart\Lk\Entity\OrderTable;
use Hogart\Lk\Entity\PdfTable;
use Hogart\Lk\Helper\Template\FlashError;

Loc::loadMessages(__FILE__);

global $USER, $CACHE_MANAGER;
$account = AccountTable::getAccountByUserID($USER->GetID());
$arParams['account'] = $account;

if ($account['id']) {

    if (!OrderTable::isHaveAccess($account['id'], intval($_REQUEST['order']))) {
        new FlashError("У вас нет доступа к заказу!");
        LocalRedirect('/account/orders/');
        return;
    }

    $arResult['order'] = OrderTable::getOrder(intval($_REQUEST['order']));
    if (!empty($arResult['order'])) {
        $arResult['order']['pdf'] = PdfTable::getByEntityClass(PdfTable::ENTITY_ORDER, intval($_REQUEST['order']));
        include __DIR__ . "/proceed_request.php";
        $APPLICATION->AddChainItem(OrderTable::showName($arResult['order']), "", false);
    }

    $this->includeComponentTemplate();
} else {
    new FlashError("У Вас нет доступа в данный раздел");
    LocalRedirect("/");
}
