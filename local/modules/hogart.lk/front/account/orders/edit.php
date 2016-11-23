<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 23/11/2016
 * Time: 12:54
 *
 * @global $APPLICATION
 */

define("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

CModule::IncludeModule('hogart.lk');

$APPLICATION->AddChainItem("Аккаунт", "/account/");
$APPLICATION->AddChainItem("Заказы", "/account/orders/");

$APPLICATION->IncludeComponent("hogart.lk:account.order.edit", "", []);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");