<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 12/10/2016
 * Time: 14:41
 *
 * @global $APPLICATION
 */
//define("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->IncludeComponent("hogart.lk:account.server.events", "", []);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
