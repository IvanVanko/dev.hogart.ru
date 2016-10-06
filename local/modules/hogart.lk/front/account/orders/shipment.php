<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 01/10/2016
 * Time: 16:50
 *
 * @global $APPLICATION
 */

define("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

CModule::IncludeModule('hogart.lk');

$APPLICATION->AddChainItem("Аккаунт", "/account/");
$APPLICATION->AddChainItem("Заказы", "/account/orders/");
$APPLICATION->AddChainItem("Отгрузка");

$APPLICATION->IncludeComponent("hogart.lk:account.order.shipment", "", []);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
