<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Продукция");
$APPLICATION->SetTitle("Продукция");

//переписываем URL для компонента catalog с помощью SetCurPage если мы перешли по URL-шаблону /brands/BRAND_CODE/CATALOG_SECTION_CODE/
//шаблон задан в URL-rewrite
//метод проперяет, что BRAND_CODE и SECTION_CODE заданы. Это произойдет тольк в случае перехода по вышеописанному шаблону.

$show_component = true;

if(!empty($_REQUEST['BRAND_CODE'])) {
    if(!HogartHelpers::rewriteBrandUrlToCatalog($_REQUEST['BRAND_CODE'], $_REQUEST['SECTION_CODE'], CATALOG_IBLOCK_ID)) {
        $show_component = false;
    };
}
else {
    //далее идет вызовы метода проверяющего что мы перешли по URL шаблон которого /catalog/SECTION_CODE_PATH/BRAND_CODE/ELEMENT_CODE/
    //что будет означать что мы перешли по URL с подраздела каталога и раздела сайта "Бренды"
    //3ий параметр служит для того чтобы передать в метод имя Поля элемента каталога, для которого построен URL ( либо ID либо CODE а зависимости от настроек инфоблока и компонента)
    if(!HogartHelpers::rewriteBrandElementUrlToCatalog(CATALOG_IBLOCK_ID, BRAND_IBLOCK_ID, 'CODE', 'brand')) {
        $show_component = false;
    };
}
?>

<? if($show_component) { ?>
    <? $APPLICATION->IncludeComponent(
        "bitrix:catalog",
        "catalog",
        Array(
            "IBLOCK_TYPE" => "catalog",
            "IBLOCK_ID" => CATALOG_IBLOCK_ID,
            "HIDE_NOT_AVAILABLE" => "N",
            "TEMPLATE_THEME" => "green",
            "COMMON_SHOW_CLOSE_POPUP" => "N",
            "SHOW_DISCOUNT_PERCENT" => "Y",
            "SHOW_OLD_PRICE" => "Y",
            "DETAIL_SHOW_MAX_QUANTITY" => "N",
            "MESS_BTN_BUY" => "Купить",
            "MESS_BTN_ADD_TO_BASKET" => "В корзину",
            "MESS_BTN_COMPARE" => "Сравнение",
            "MESS_BTN_DETAIL" => "Подробнее",
            "MESS_NOT_AVAILABLE" => "Нет в наличии",
            "DETAIL_USE_VOTE_RATING" => "N",
            "DETAIL_USE_COMMENTS" => "N",
            "DETAIL_BRAND_USE" => "N",
            "SEF_MODE" => "Y",
            "AJAX_MODE" => "N",
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "Y",
            "AJAX_OPTION_HISTORY" => "N",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "36000000",
            "CACHE_FILTER" => "Y",
            "CACHE_GROUPS" => "Y",
            "SET_STATUS_404" => "Y",
            "SET_TITLE" => "Y",
            "ADD_SECTIONS_CHAIN" => "Y",
            "ADD_ELEMENT_CHAIN" => "Y",
            "USE_ELEMENT_COUNTER" => "N",
            "USE_SALE_BESTSELLERS" => "N",
            "USE_FILTER" => "Y",
            "FILTER_VIEW_MODE" => "VERTICAL",
            "ACTION_VARIABLE" => "action",
            "PRODUCT_ID_VARIABLE" => "id",
            "USE_COMPARE" => "N",
            "PRICE_CODE" => array('BASE'),
            "USE_PRICE_COUNT" => "N",
            "SHOW_PRICE_COUNT" => "1",
            "PRICE_VAT_INCLUDE" => "Y",
            "PRICE_VAT_SHOW_VALUE" => "N",
            "CONVERT_CURRENCY" => "Y",
            "BASKET_URL" => "/personal/basket.php",
            "USE_PRODUCT_QUANTITY" => "N",
            "ADD_PROPERTIES_TO_BASKET" => "Y",
            "PRODUCT_PROPS_VARIABLE" => "prop",
            "PARTIAL_PRODUCT_PROPERTIES" => "N",
            "PRODUCT_PROPERTIES" => array(),
            "USE_COMMON_SETTINGS_BASKET_POPUP" => "N",
            "TOP_ADD_TO_BASKET_ACTION" => "ADD",
            "SECTION_ADD_TO_BASKET_ACTION" => "ADD",
            "DETAIL_ADD_TO_BASKET_ACTION" => array("BUY"),
            "SHOW_TOP_ELEMENTS" => "N",
            "TOP_ELEMENT_COUNT" => "9",
            "TOP_LINE_ELEMENT_COUNT" => "3",
            "TOP_ELEMENT_SORT_FIELD" => "shows",
            "TOP_ELEMENT_SORT_ORDER" => "asc",
            "TOP_ELEMENT_SORT_FIELD2" => "id",
            "TOP_ELEMENT_SORT_ORDER2" => "desc",
            "TOP_PROPERTY_CODE" => array("", "undefined", ""),
            "SECTION_COUNT_ELEMENTS" => "Y",
            "SECTION_TOP_DEPTH" => "2",
            "SECTIONS_VIEW_MODE" => "LIST",
            "SECTIONS_SHOW_PARENT_NAME" => "Y",
            "PAGE_ELEMENT_COUNT" => "30",
            "LINE_ELEMENT_COUNT" => "3",
            "ELEMENT_SORT_FIELD" => "sort",
            "ELEMENT_SORT_ORDER" => "asc",
            "ELEMENT_SORT_FIELD2" => "id",
            "ELEMENT_SORT_ORDER2" => "desc",
            "LIST_PROPERTY_CODE" => array("brand", "collection"),
            "INCLUDE_SUBSECTIONS" => "Y",
            "LIST_META_KEYWORDS" => "-",
            "LIST_META_DESCRIPTION" => "-",
            "LIST_BROWSER_TITLE" => "-",
            "DETAIL_PROPERTY_CODE" => array("photos", "collection", "brand", "sku", "undefined", ""),
            "DETAIL_META_KEYWORDS" => "-",
            "DETAIL_META_DESCRIPTION" => "-",
            "DETAIL_BROWSER_TITLE" => "-",
            "SECTION_ID_VARIABLE" => "SECTION_ID",
            "DETAIL_CHECK_SECTION_ID_VARIABLE" => "N",
            "DETAIL_DISPLAY_NAME" => "Y",
            "DETAIL_DETAIL_PICTURE_MODE" => "IMG",
            "DETAIL_ADD_DETAIL_TO_SLIDER" => "N",
            "DETAIL_DISPLAY_PREVIEW_TEXT_MODE" => "E",
            "LINK_IBLOCK_TYPE" => "undefined",
            "LINK_IBLOCK_ID" => "undefined",
            "LINK_PROPERTY_SID" => "undefined",
            "LINK_ELEMENTS_URL" => "link.php?PARENT_ELEMENT_ID=#ELEMENT_ID#",
            "USE_ALSO_BUY" => "N",
            "USE_STORE" => "N",
            "USE_BIG_DATA" => "Y",
            "BIG_DATA_RCM_TYPE" => "bestsell",
            "PAGER_TEMPLATE" => ".default",
            "DISPLAY_TOP_PAGER" => "N",
            "DISPLAY_BOTTOM_PAGER" => "Y",
            "PAGER_TITLE" => "Товары",
            "PAGER_SHOW_ALWAYS" => "N",
            "PAGER_DESC_NUMBERING" => "N",
            "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
            "PAGER_SHOW_ALL" => "N",
            "ADD_PICT_PROP" => "-",
            "LABEL_PROP" => "-",
            "TOP_VIEW_MODE" => "SECTION",
            "SEF_FOLDER" => "/catalog/",
            "SEF_URL_TEMPLATES" => Array("sections" => "",
                                         "section" => "#SECTION_CODE_PATH#/",
                                         "element" => "#SECTION_CODE_PATH#/#BRAND#/#ELEMENT_CODE#/",
                                         "compare" => "compare.php?action=#ACTION_CODE#"),
            "FILTER_NAME" => "",
            "FILTER_FIELD_CODE" => array("", "undefined", ""),
            "FILTER_PROPERTY_CODE" => array("", "undefined", ""),
            "FILTER_PRICE_CODE" => array(),
            "CURRENCY_ID" => "RUB",
            "SECTIONS_HIDE_SECTION_NAME" => "N",
            "VARIABLE_ALIASES" => Array("sections" => Array(),
                                        "section" => Array(),
                                        "element" => Array(),
                                        "compare" => Array("ACTION_CODE" => "action"),),
            "VARIABLE_ALIASES" => Array(
                "sections" => Array(),
                "section" => Array(),
                "element" => Array(),
                "compare" => Array(
                    "ACTION_CODE" => "action"
                ),
            )
        )
    ); ?>
<? }
else {
    BXHelper::NotFound();
} ?>
<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>