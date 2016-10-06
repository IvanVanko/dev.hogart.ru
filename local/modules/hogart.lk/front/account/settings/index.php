<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 15/08/16
 * Time: 15:17
 * @global CMain $APPLICATION
 */
define("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

CModule::IncludeModule('hogart.lk');

$APPLICATION->AddChainItem("Аккаунт");
$APPLICATION->AddChainItem("Настройки");

$APPLICATION->IncludeComponent("hogart.lk:account.settings", "", []);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); 
