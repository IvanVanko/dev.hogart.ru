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
use Hogart\Lk\Entity\OrderEditTable;
use Hogart\Lk\Helper\Template\FlashError;
use Hogart\Lk\Exchange\SOAP\Client as SoapClient;

Loc::loadMessages(__FILE__);

global $USER, $CACHE_MANAGER;
$account = \Hogart\Lk\Helper\Template\Account::getAccount();
$arParams['account'] = $account;

if ($account['id']) {

    if (!OrderTable::isHaveAccess($account['id'], intval($_REQUEST['order']))) {
        new FlashError("У вас нет доступа к заказу!");
        LocalRedirect('/account/orders/');
        return;
    }

    $order = OrderTable::getRowById(intval($_REQUEST['order']));

    try {
        SoapClient::getInstance()->Order->blockOrder($order['guid_id'], $account['user_guid_id']);
    } catch (\Exception $e) {
        new FlashError($e->getMessage());
        LocalRedirect('/account/order/' . intval($_REQUEST['order']) . '/');
        return;
    }

    if (!$order['is_actual']) {
        new FlashError("На данный момент заказ на синхронизации");
        LocalRedirect('/account/order/' . intval($_REQUEST['order']) . '/');
        return;
    }

    include __DIR__ . "/proceed_request.php";

    $arResult['order'] = OrderEditTable::getOrder(intval($_REQUEST['order']));
    if (empty($arResult['order'])) {
        $arResult['order'] = OrderEditTable::copyFromOrder(intval($_REQUEST['order']));
    }

    if (!empty($arResult['order'])) {
        $APPLICATION->AddChainItem(OrderTable::showName($arResult['order'], '_'), "/account/order/" . intval($_REQUEST['order']), false);
        $APPLICATION->AddChainItem("Редактирование", "", false);
    }

    $this->includeComponentTemplate();
} else {
    new FlashError("У Вас нет доступа в данный раздел");
    LocalRedirect("/");
}
