<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 12/09/16
 * Time: 23:45
 *
 * @global CMain $APPLICATION
 */

define("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->AddChainItem("Аккаунт");
$APPLICATION->AddChainItem("Корзина");

$APPLICATION->IncludeComponent("hogart.lk:account.cart.list", "", [
    'SEF_URL' => '/account/cart/'
]);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
