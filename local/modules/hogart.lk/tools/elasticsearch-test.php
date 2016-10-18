<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 14/10/2016
 * Time: 18:26
 */

$_SERVER["DOCUMENT_ROOT"] = realpath(__DIR__ . "/../../../../");
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
define("ADMIN_SECTION", true);
set_time_limit(0);
ini_set("xdebug.var_display_max_depth", -1);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule("hogart.lk");
CModule::IncludeModule("main");
CModule::IncludeModule("catalog");

use Hogart\Lk\Search\CartSuggest;

CartSuggest::getInstance()->deleteIndex();
CartSuggest::getInstance()->createIndex();
CartSuggest::getInstance()->indexAll();
//var_dump(CartSuggest::getInstance()->search('термос 80'));

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");


