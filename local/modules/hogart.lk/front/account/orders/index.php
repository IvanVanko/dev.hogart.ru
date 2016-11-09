<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 26/09/2016
 * Time: 22:11
 *
 * @global $APPLICATION
 */

define("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

CModule::IncludeModule('hogart.lk');

$APPLICATION->AddChainItem("Аккаунт");
$APPLICATION->AddChainItem("Заказы");

$state = (string)$_REQUEST['state'];

if (empty($state)) {
    LocalRedirect($APPLICATION->GetCurPage(false) . "active/");
    die();
}

$APPLICATION->IncludeComponent("hogart.lk:account.order.list", "", [
    'STATE' => $state
]);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");