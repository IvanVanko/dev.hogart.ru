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

LocalRedirect($APPLICATION->GetCurPage(false) . "settings/");

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); 
