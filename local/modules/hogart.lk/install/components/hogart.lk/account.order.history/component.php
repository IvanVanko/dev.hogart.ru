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

use Hogart\Lk\Entity\AccountTable;
use Hogart\Lk\Entity\OrderTable;
use Hogart\Lk\Helper\Template\FlashError;
use Hogart\Lk\Entity\OrderEventTable;


global $USER, $CACHE_MANAGER;
$account = \Hogart\Lk\Helper\Template\Account::getAccount();
$arParams['account'] = $account;

if ($account['id']) {

    if (!OrderTable::isHaveAccess($account['id'], intval($_REQUEST['order']))) {
        new FlashError("У вас нет доступа к заказу!");
        LocalRedirect('/account/orders/');
        return;
    }

    $arResult['order'] = OrderTable::getOrder(intval($_REQUEST['order']));
    if (!empty($arResult['order'])) {
        $APPLICATION->AddChainItem(OrderTable::showName($arResult['order']), "/account/order/" . $arResult['order']['id'] . "/", false);
        $APPLICATION->AddChainItem("История");

        $arResult['history'] = OrderEventTable::getOrderHistory($arResult['order']['id']);
    }
    $this->includeComponentTemplate();
} else {
    new FlashError("У Вас нет доступа в данный раздел");
    LocalRedirect("/");
}
