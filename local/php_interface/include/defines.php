<?php
include_once __DIR__."/classes/iblock_tools.php";

define('DEFAULT_TEMPLATE_PATH',"/local/templates/.default/");
define('DEFAULT_TEMPLATE_PATH_CUSTOM',"/local/templates/.default/");
define("STATIC_PATH","/static/");

define("GPS_S_REGEXP",'/(<td>GPS долгота:<\/td>[\x00-\x1F\x80-\x9F ]*.*[\x00-\x1F\x80-\x9F ]*<\/td>[\x00-\x1F\x80-\x9F ]*<\/tr>)/u');
define("GPS_N_REGEXP",'/(<td>GPS широта:<\/td>[\x00-\x1F\x80-\x9F ]*.*[\x00-\x1F\x80-\x9F ]*<\/td>)/u');


define("REGISTER_SUBMIT",intval(isset($_REQUEST["register_submit_button"])));
define("LOGIN_SUBMIT",intval(isset($_POST["Login"])));
define("FORGOT_PASS_SUBMIT",intval(isset($_REQUEST["AUTH_FORM"]) && $_REQUEST["TYPE"] == "SEND_PWD"));

if ($GLOBALS['APPLICATION']->GetCurPage() == "/search/") {
    define('IS_SEARCH_PAGE', 1);
}

define("CATALOG_IBLOCK_ID", 1);
define("BRAND_IBLOCK_ID", (LANGUAGE_ID == 'en' ? 32 : 2));
define("CATALOG_BRAND_PROPERTY_CODE", "brand");
define("SEMINAR_IBLOCK_ID", 8);
define("EQUIPMENT_SELECTION_IBLOCK_ID", 12);
define("COLLECTION_IBLOCK_ID", 22);
define("REVIEWS_IBLOCK_ID", 29);
define("EVENTS_IBLOCK_ID", CIBlockTools::GetIBlockId('events'));
define("EVENTS_ORGANIZER_IBLOCK_ID", CIBlockTools::GetIBlockId('event_organizer'));
define("EVENTS_FORM_RESULT_IBLOCK_ID", CIBlockTools::GetIBlockId('event_form_result'));
define("SEMINAR_LINK_INPUT_REGEXP", "<tr class=\"adm-detail-required-field\">\n.*\n.*.*SEMINAR_ID.*\n<input .*\n.*\n");
