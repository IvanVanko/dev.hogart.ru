<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 15/08/16
 * Time: 15:17
 */
define("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

CModule::IncludeModule('hogart.lk');

$APPLICATION->AddChainItem("Аккаунт");
$APPLICATION->AddChainItem("Настройки");

if (!$APPLICATION->IncludeComponent("hogart.lk:account.settings", "", [])) {
    BXHelper::NotFound();
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); 
