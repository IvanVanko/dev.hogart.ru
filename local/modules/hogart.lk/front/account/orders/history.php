<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 03/10/2016
 * Time: 01:40
 *
 * @global $APPLICATION
 */

define("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

CModule::IncludeModule('hogart.lk');

$APPLICATION->AddChainItem("Аккаунт", "/account/");
$APPLICATION->AddChainItem("Заказы", "/account/orders/");

$APPLICATION->IncludeComponent("hogart.lk:account.order.history", "", []);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
