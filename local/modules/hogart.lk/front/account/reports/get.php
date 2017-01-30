<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 19/01/2017
 * Time: 01:24
 *
 * @global $APPLICATION
 */

define("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

CModule::IncludeModule('hogart.lk');

$APPLICATION->AddChainItem("Аккаунт", "/account/");
$APPLICATION->AddChainItem("Отчеты", "/account/reports/");

$APPLICATION->IncludeComponent("hogart.lk:account.report.get", "", []);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");