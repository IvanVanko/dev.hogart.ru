<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Реализованные проекты");
?><?
/*$APPLICATION->IncludeComponent("kontora:section.detail", "solution", array(
  'SECTION_CODE' => $_REQUEST['section'],
  'SELECT'       => array('UF_*', 'ID', 'IBLOCK_ID', 'NAME', 'DESCRIPTION')
));*/
$section_codes = BXHelper::getSections(array('ID' => 'ASC'), array('ACTIVE' => 'Y', 'IBLOCK_ID' => '7'), false, array('CODE'), true, 'CODE');
$section_codes = array_keys($section_codes['RESULT']);
if (in_array($_REQUEST["SECTION_CODE"], $section_codes)) {
    $APPLICATION->IncludeComponent(
        "bitrix:catalog.section",
        "solution",
        Array(
            "COMPONENT_TEMPLATE" => ".default",
            "IBLOCK_TYPE" => "solutions",
            "IBLOCK_ID" => "7",
            "SECTION_ID" => "",
            "SECTION_CODE" => $_REQUEST["SECTION_CODE"],
            "SECTION_USER_FIELDS" => array("",""),
            "ELEMENT_SORT_FIELD" => "sort",
            "ELEMENT_SORT_ORDER" => "asc",
            "ELEMENT_SORT_FIELD2" => "id",
            "ELEMENT_SORT_ORDER2" => "desc",
            "FILTER_NAME" => "arrFilter",
            "INCLUDE_SUBSECTIONS" => "Y",
            "SHOW_ALL_WO_SECTION" => "N",
            "HIDE_NOT_AVAILABLE" => "N",
            "PAGE_ELEMENT_COUNT" => "30",
            "LINE_ELEMENT_COUNT" => "3",
            "PROPERTY_CODE" => array("",""),
            "OFFERS_LIMIT" => "5",
            "TEMPLATE_THEME" => "blue",
            "PRODUCT_SUBSCRIPTION" => "N",
            "SHOW_DISCOUNT_PERCENT" => "N",
            "SHOW_OLD_PRICE" => "N",
            "SHOW_CLOSE_POPUP" => "N",
            "MESS_BTN_BUY" => "Купить",
            "MESS_BTN_ADD_TO_BASKET" => "В корзину",
            "MESS_BTN_SUBSCRIBE" => "Подписаться",
            "MESS_BTN_DETAIL" => "Подробнее",
            "MESS_NOT_AVAILABLE" => "Нет в наличии",
            "SECTION_URL" => "",
            "DETAIL_URL" => "",
            "SECTION_ID_VARIABLE" => "SECTION_ID",
            "AJAX_MODE" => "N",
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "Y",
            "AJAX_OPTION_HISTORY" => "N",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "36000000",
            "CACHE_GROUPS" => "Y",
            "SET_TITLE" => "Y",
            "SET_BROWSER_TITLE" => "Y",
            "BROWSER_TITLE" => "-",
            "SET_META_KEYWORDS" => "Y",
            "META_KEYWORDS" => "-",
            "SET_META_DESCRIPTION" => "Y",
            "META_DESCRIPTION" => "-",
            "ADD_SECTIONS_CHAIN" => "N",
            "SET_STATUS_404" => "N",
            "CACHE_FILTER" => "N",
            "ACTION_VARIABLE" => "action",
            "PRODUCT_ID_VARIABLE" => "id",
            "PRICE_CODE" => array(),
            "USE_PRICE_COUNT" => "N",
            "SHOW_PRICE_COUNT" => "1",
            "PRICE_VAT_INCLUDE" => "Y",
            "CONVERT_CURRENCY" => "N",
            "BASKET_URL" => "",
            "USE_PRODUCT_QUANTITY" => "N",
            "ADD_PROPERTIES_TO_BASKET" => "Y",
            "PRODUCT_PROPS_VARIABLE" => "prop",
            "PARTIAL_PRODUCT_PROPERTIES" => "N",
            "PRODUCT_PROPERTIES" => array(),
            "ADD_TO_BASKET_ACTION" => "ADD",
            "DISPLAY_COMPARE" => "N",
            "PAGER_TEMPLATE" => ".default",
            "DISPLAY_TOP_PAGER" => "N",
            "DISPLAY_BOTTOM_PAGER" => "Y",
            "PAGER_TITLE" => "Товары",
            "PAGER_SHOW_ALWAYS" => "N",
            "PAGER_DESC_NUMBERING" => "N",
            "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
            "PAGER_SHOW_ALL" => "N",
            "MESS_BTN_COMPARE" => "Сравнить",
            "ADD_PICT_PROP" => "-",
            "LABEL_PROP" => "-"
        )
    );
} else {
    BXHelper::NotFound();
}


require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");?>