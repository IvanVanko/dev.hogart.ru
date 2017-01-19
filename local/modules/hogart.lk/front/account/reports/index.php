<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 17/01/2017
 * Time: 13:23
 *
 * @global $APPLICATION
 *
 */

define("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

CModule::IncludeModule('hogart.lk');

$APPLICATION->AddChainItem("Аккаунт");
$APPLICATION->AddChainItem("Отчеты");

$APPLICATION->IncludeComponent("hogart.lk:account.report.list", "");

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");