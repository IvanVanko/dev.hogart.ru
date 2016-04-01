<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;
$this->setFrameMode(true);
global $elementFilter;

//метод устанавливает параметр в REQUEST для принудительной фильтрации по брендам если перешли на подраздел из карточки бренда
HogartHelpers::setBrandRequestFilter(CStorage::getVar('CATALOG_BRAND_CODE'), BRAND_IBLOCK_ID, CATALOG_IBLOCK_ID, CATALOG_BRAND_PROPERTY_CODE);
$section = BXHelper::getSections(array(), array('ID' => $arResult["VARIABLES"]["SECTION_ID"], 'IBLOCK_ID' => $arParams['IBLOCK_ID']), false, array('ID','DEPTH_LEVEL'));
$section = $section['RESULT'][0];

//Определим уровень вложенности теекущего раздела
//$sections = BXHelper::getSections(array(), array('IBLOCK_ID' => ));
/*$arFilter = array('IBLOCK_ID' => $arParams["IBLOCK_ID"], 'CODE' => $arResult["VARIABLES"]["SECTION_CODE"]);
$rsSect = CIBlockSection::GetList(array('left_margin' => 'asc'),$arFilter);
if ($arSect = $rsSect->GetNext()) {
	$sectionID = $arSect['ID'];
	$curSection = $arSect;
}*/

if ($arParams['USE_FILTER'] == 'Y')
{
    ob_start();
    $arFilter = array(
        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
        "ACTIVE" => "Y",
        "GLOBAL_ACTIVE" => "Y",
    );
    if (0 < intval($arResult["VARIABLES"]["SECTION_ID"]))
    {
        $arFilter["ID"] = $arResult["VARIABLES"]["SECTION_ID"];
    }
    elseif ('' != $arResult["VARIABLES"]["SECTION_CODE"])
    {
        $arFilter["=CODE"] = $arResult["VARIABLES"]["SECTION_CODE"];
    }

    $obCache = new CPHPCache();
    if ($obCache->InitCache(36000, serialize($arFilter), "/iblock/catalog"))
    {
        $arCurSection = $obCache->GetVars();
    }
    elseif ($obCache->StartDataCache())
    {
        $arCurSection = array();
        if (Loader::includeModule("iblock"))
        {
            $dbRes = CIBlockSection::GetList(array(), $arFilter, false, array("ID"));

            if(defined("BX_COMP_MANAGED_CACHE"))
            {
                global $CACHE_MANAGER;
                $CACHE_MANAGER->StartTagCache("/iblock/catalog");

                if ($arCurSection = $dbRes->Fetch())
                {
                    $CACHE_MANAGER->RegisterTag("iblock_id_".$arParams["IBLOCK_ID"]);
                }
                $CACHE_MANAGER->EndTagCache();
            }
            else
            {
                if(!$arCurSection = $dbRes->Fetch())
                    $arCurSection = array();
            }
        }
        $obCache->EndDataCache($arCurSection);
    }
    if (!isset($arCurSection))
    {
        $arCurSection = array();
    }
    ?>
    <?
    //доработка фильтра для фильтрации складов
    //здесь получаем склады на сайте. Флаг UF_TRANSIT говорит о том, что склад относится к пункту Складская программа. Остальные - Есть в наличии
    $stores = BXHelper::getStores(array(), array(), false,false, array('ID','UF_TRANSIT','TITLE'), 'ID');


    //получаем request филтьра складов и дополняем $stores флагами SELECTED тех складов которые выбрали для фильтрации
    $arFilterStores = $_REQUEST['arrFilter_stores'];
    $selected_stores = array();
    foreach ($arFilterStores as $arFilterStoreGroup) {
        $selected_stores = array_merge(explode(",",$arFilterStoreGroup),$stores);
    }


    if (is_array($selected_stores) && count($selected_stores)) {
        foreach ($selected_stores as $store_id) {
            if (!isset($stores[$store_id]['SELECTED'])) {
                $stores[$store_id]['SELECTED'] = intval(!empty($stores[$store_id]));
            }
        }
    }



    //сфомированный массив $stores передаем в компонент. Далее СМ. result_modifier в шаблоне


    //получаем свойства типа Min Max для формирования из них ползунков типа диапазон и передаем в компонент. далее смотри result_modifier.php
    $range_groups = HogartHelpers::getRangePropertyGroupsForFilter();

    ?>
    <?/*проверяем на depth_level потому что (1): нам не нужен никогда фильтр  категориях 1 уровня (2): слишком много свойств получается и огроменные запросы*/?>
    <?if ($section['DEPTH_LEVEL'] != 1) $APPLICATION->IncludeComponent(
    "bitrix:catalog.smart.filter",
    "",
    array(
        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
        "SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
        "FILTER_NAME" => $arParams["FILTER_NAME"],
        "PRICE_CODE" => $arParams["PRICE_CODE"],
        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
        "CACHE_TIME" => $arParams["CACHE_TIME"],
        "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
        "SAVE_IN_SESSION" => "N",
        "FILTER_VIEW_MODE" => $arParams["FILTER_VIEW_MODE"],
        "XML_EXPORT" => "Y",
        "SECTION_TITLE" => "NAME",
        "SECTION_DESCRIPTION" => "DESCRIPTION",
        'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
        "TEMPLATE_THEME" => $arParams["TEMPLATE_THEME"],
        'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
        'CURRENCY_ID' => $arParams['CURRENCY_ID'],
        "SEF_MODE" => $arParams["SEF_MODE"],
        "SEF_RULE" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["smart_filter"],
        "SMART_FILTER_PATH" => $arResult["VARIABLES"]["SMART_FILTER_PATH"],
        "PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
        "RANGE_GROUPS" => $range_groups,
        "STORES" => $stores,
        "SELECTED_WAREHOUSE" =>  $_REQUEST['arrFilter_warehouse']

    ),
    $component,
    array('HIDE_ICONS' => 'Y')
    );
    $FILTER_HTML = ob_get_clean();

    $arCustomFilter = CStorage::getVar('CUSTOM_SECTION_FILTER');
    if (!empty($arCustomFilter)) {
        if (empty($GLOBALS[$arParams["FILTER_NAME"]])) {
            $GLOBALS[$arParams["FILTER_NAME"]] = array();
        }
        $GLOBALS[$arParams["FILTER_NAME"]] = array_merge($GLOBALS[$arParams["FILTER_NAME"]], (array)$arCustomFilter);
    }
}
$stores_filtered = CStorage::getVar('ACTIVE_STORES_FILTERED');


ob_start();

if (isset($arParams['USE_COMMON_SETTINGS_BASKET_POPUP']) && $arParams['USE_COMMON_SETTINGS_BASKET_POPUP'] == 'Y')
{
    $basketAction = (isset($arParams['COMMON_ADD_TO_BASKET_ACTION']) ? $arParams['COMMON_ADD_TO_BASKET_ACTION'] : '');
}
else
{
    $basketAction = (isset($arParams['SECTION_ADD_TO_BASKET_ACTION']) ? $arParams['SECTION_ADD_TO_BASKET_ACTION'] : '');
}
$intSectionID = 0;
?>
<?if (!empty($_REQUEST['stock'])) {
    $ids = array();
    foreach ($_REQUEST['stock'] as $id) {
        $arFilter = array("ID" => $id);
        $res = CIBlockElement::GetList(array('sort' => 'asc'), $arFilter, false, false, array("PROPERTY_goods"));
        while ($ob = $res->GetNextElement()) {
            $arFields = $ob->GetFields();
            $ids[] = $arFields['PROPERTY_GOODS_VALUE'];
        }
    }
    $GLOBALS[$arParams["FILTER_NAME"]]["ID"] = $ids;
}?>
<?//Sort
if (!empty($_REQUEST['sort']) && isset($_REQUEST['sort'])) {
    $sort = $_REQUEST['sort'];
    $order = $_REQUEST['order'];
}
else {
    $sort = 'shows';
    $order = 'desc';
}

//бюро пирогова
if (empty($arResult['VARIABLES']['SECTION_ID']) && empty($arResult['VARIABLES']['SECTION_CODE'])) {
    $arResult['VARIABLES']['SECTION_CODE'] = 'NULL';
}

$intSectionID = $APPLICATION->IncludeComponent(
    "bitrix:catalog.section",
    "hogart_catalog_section",
    array(
        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
        "ELEMENT_SORT_FIELD" => $sort,
        "ELEMENT_SORT_ORDER" => $order,
        "ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
        "ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
        "PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
        "META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
        "META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
        "BROWSER_TITLE" => $arParams["LIST_BROWSER_TITLE"],
        "INCLUDE_SUBSECTIONS" => $section['DEPTH_LEVEL'] > 1 ? $arParams["INCLUDE_SUBSECTIONS"]:"N",
        "BASKET_URL" => $arParams["BASKET_URL"],
        "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
        "PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
        "SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
        "PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
        "PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
        "FILTER_NAME" => $arParams["FILTER_NAME"],
        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
        "CACHE_TIME" => $arParams["CACHE_TIME"],
        "CACHE_FILTER" => $arParams["CACHE_FILTER"],
        "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
        "SET_TITLE" => $arParams["SET_TITLE"],
        "SET_STATUS_404" => $arParams["SET_STATUS_404"],
        "DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
        "PAGE_ELEMENT_COUNT" => $arParams["PAGE_ELEMENT_COUNT"],
        "LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
        "PRICE_CODE" => $arParams["PRICE_CODE"],
        "USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
        "SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],

        "PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
        "USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
        "ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
        "PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
        "PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],

        "DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
        "DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
        "PAGER_TITLE" => $arParams["PAGER_TITLE"],
        "PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
        "PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
        "PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
        "PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
        "PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],

        "OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
        "OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
        "OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],
        "OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
        "OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
        "OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
        "OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
        "OFFERS_LIMIT" => $arParams["LIST_OFFERS_LIMIT"],

        "SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
        "SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
        "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
        "DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
        'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
        'CURRENCY_ID' => $arParams['CURRENCY_ID'],
        'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],

        'LABEL_PROP' => $arParams['LABEL_PROP'],
        'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
        'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],

        'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
        'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
        'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
        'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
        'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
        'MESS_BTN_BUY' => $arParams['MESS_BTN_BUY'],
        'MESS_BTN_ADD_TO_BASKET' => $arParams['MESS_BTN_ADD_TO_BASKET'],
        'MESS_BTN_SUBSCRIBE' => $arParams['MESS_BTN_SUBSCRIBE'],
        'MESS_BTN_DETAIL' => $arParams['MESS_BTN_DETAIL'],
        'MESS_NOT_AVAILABLE' => $arParams['MESS_NOT_AVAILABLE'],

        'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
        "ADD_SECTIONS_CHAIN" => "Y",
        'ADD_TO_BASKET_ACTION' => $basketAction,
        'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
        'COMPARE_PATH' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['compare'],

        //template.php, result_modifier.php
        'HREF_BRAND_CODE' => CStorage::getVar('CATALOG_BRAND_CODE'),
        'STORES_FILTERED' => $stores_filtered,
        'VIEW_TYPE' => CStorage::getCookieParam('catalog-view-type', 'list')
    ),
    $component
);
$SECTION_HTML = ob_get_clean();
$DEPTH_LEVEL = intval(CStorage::getVar('SECTION_DEPTH_LEVEL'));

//установка кастомных мета тегов в том случае если мы перешли в брендированный раздел
$section_custom_meta = CStorage::getVar('SECTION_CUSTOM_META');

if (!empty($section_custom_meta)) {
    foreach ($section_custom_meta as $meta_name => $meta_string) {
        if (!empty($meta_string)) {
            $APPLICATION->SetPageProperty($meta_name, $meta_string);
        }
    }
}

$GLOBALS['CATALOG_CURRENT_SECTION_ID'] = $intSectionID;
unset($basketAction);

ob_start();
$elementFilter = array(
    array(
        "LOGIC" => "OR",
        array('PROPERTY_section' => CStorage::getVar('CURRENT_SECTION_ELEMENTS') /*устанавливается в component_epilog catalog.section - sasha*/),
        array("PROPERTY_catalog_section" => $intSectionID),
    )
);
$APPLICATION->IncludeComponent(
    "bitrix:news.list",
    "news_list",
    Array(
        "COMPONENT_TEMPLATE" => "news_list",
        "IBLOCK_TYPE" => "news",
        "IBLOCK_ID" => 6,
        "NEWS_COUNT" => "2",
//        "SORT_BY1" => "ACTIVE_FROM",
        "SORT_BY1" => "ACTIVE_FROM",
        "SORT_ORDER1" => "DESC",
        "SORT_BY2" => "SORT",
        "SORT_ORDER2" => "ASC",
        "FILTER_NAME" => "elementFilter",
        "FIELD_CODE" => array("",""),
        "PROPERTY_CODE" => array("",""),
        "CHECK_DATES" => "Y",
        "DETAIL_URL" => "",
        "AJAX_MODE" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "AJAX_OPTION_HISTORY" => "N",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "36000000",
        "CACHE_FILTER" => "N",
        "CACHE_GROUPS" => "Y",
        "PREVIEW_TRUNCATE_LEN" => "",
        "ACTIVE_DATE_FORMAT" => "d.m.Y",
        "SET_TITLE" => "N",
        "SET_BROWSER_TITLE" => "N",
        "SET_META_KEYWORDS" => "N",
        "SET_META_DESCRIPTION" => "N",
        "SET_STATUS_404" => "N",
        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
        "ADD_SECTIONS_CHAIN" => "N",
        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
        "PARENT_SECTION" => "",
        "PARENT_SECTION_CODE" => "",
        "INCLUDE_SUBSECTIONS" => "Y",
        "DISPLAY_DATE" => "Y",
        "DISPLAY_NAME" => "Y",
        "DISPLAY_PICTURE" => "Y",
        "DISPLAY_PREVIEW_TEXT" => "Y",
        "PAGER_TEMPLATE" => ".default",
        "DISPLAY_TOP_PAGER" => "N",
        "DISPLAY_BOTTOM_PAGER" => "Y",
        "PAGER_TITLE" => "Новости",
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_DESC_NUMBERING" => "N",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
        "PAGER_SHOW_ALL" => "N",

        //custom
        "SHOW_PERIOD" => "Y",
        "DISPLAY_LINK_HTML" => "<a class=\"more-detail\" href=\"/stock/\">Все акции</a>",
        "WRAPPER_CSS" => "small-news-cnt"
    )
);

$promos_html = ob_get_clean();

if($DEPTH_LEVEL == 1) {?>
<div class="inner category padding20">
    <?/*$APPLICATION->IncludeComponent("bitrix:breadcrumb","",Array(
	        "START_FROM" => "1",
	        "PATH" => "",
	        "SITE_ID" => SITE_ID
	    )
	);*/?>
	<?$APPLICATION->IncludeComponent(
		"bitrix:catalog.section.list",
		"section",
		array(
			"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],
			"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
			"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
            "SELECTED_SORT_FIELD" => "NAME",
            "SELECTED_SORT_ORDER" => "ASC",
			"CACHE_TYPE" => $arParams["CACHE_TYPE"],
			"CACHE_TIME" => $arParams["CACHE_TIME"],
			"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
			"COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
			"TOP_DEPTH" => $arParams["SECTION_TOP_DEPTH"],
			"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
			"VIEW_MODE" => $arParams["SECTIONS_VIEW_MODE"],
			"SHOW_PARENT_NAME" => $arParams["SECTIONS_SHOW_PARENT_NAME"],
			"HIDE_SECTION_NAME" => (isset($arParams["SECTIONS_HIDE_SECTION_NAME"]) ? $arParams["SECTIONS_HIDE_SECTION_NAME"] : "N"),
			"ADD_SECTIONS_CHAIN" => "N"
		),
		$component,
		array("HIDE_ICONS" => "Y")
	);?>
</div>
<?}?>
<?$APPLICATION->ShowViewContent('top_section_wrapper')?>
    <?
    if ($DEPTH_LEVEL == 1) {
        $elementFilter = array('PROPERTY_catalog_section' => $intSectionID);
        $APPLICATION->IncludeComponent(
            "bitrix:news.list",
            "news_list",
            Array(
                "COMPONENT_TEMPLATE" => "news_list",
                "IBLOCK_TYPE" => "news",
                "IBLOCK_ID" => 3,
                "NEWS_COUNT" => "2",
                "SORT_BY1" => "ACTIVE_FROM",
                "SORT_ORDER1" => "DESC",
                "SORT_BY2" => "SORT",
                "SORT_ORDER2" => "ASC",
                "FILTER_NAME" => "elementFilter",
                "FIELD_CODE" => array("",""),
                "PROPERTY_CODE" => array("",""),
                "CHECK_DATES" => "Y",
                "DETAIL_URL" => "",
                "AJAX_MODE" => "N",
                "AJAX_OPTION_JUMP" => "N",
                "AJAX_OPTION_STYLE" => "Y",
                "AJAX_OPTION_HISTORY" => "N",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "36000000",
                "CACHE_FILTER" => "N",
                "CACHE_GROUPS" => "Y",
                "PREVIEW_TRUNCATE_LEN" => "",
                "ACTIVE_DATE_FORMAT" => "d.m.Y",
                "SET_TITLE" => "N",
                "SET_BROWSER_TITLE" => "N",
                "SET_META_KEYWORDS" => "N",
                "SET_META_DESCRIPTION" => "N",
                "SET_STATUS_404" => "N",
                "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                "ADD_SECTIONS_CHAIN" => "N",
                "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                "PARENT_SECTION" => "",
                "PARENT_SECTION_CODE" => "",
                "INCLUDE_SUBSECTIONS" => "Y",
                "DISPLAY_DATE" => "Y",
                "DISPLAY_NAME" => "Y",
                "DISPLAY_PICTURE" => "Y",
                "DISPLAY_PREVIEW_TEXT" => "Y",
                "PAGER_TEMPLATE" => ".default",
                "DISPLAY_TOP_PAGER" => "N",
                "DISPLAY_BOTTOM_PAGER" => "Y",
                "PAGER_TITLE" => "Новости",
                "PAGER_SHOW_ALWAYS" => "N",
                "PAGER_DESC_NUMBERING" => "N",
                "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                "PAGER_SHOW_ALL" => "N",

                //custom
                "DISPLAY_LINK_HTML" => "<a class=\"more-detail\" href=\"/company/news/\">Все новости</a>"
            )
        );
    } elseif ($DEPTH_LEVEL >= 2)
    {?>
            <div class="sidebar_padding_cnt">
                <div class="filter_cnt">
                    <?=$FILTER_HTML?>
                </div>
            </div>
        <?}print($promos_html) //АКЦИИ?>
    </div>
</aside>
<?if ($DEPTH_LEVEL > 1) {?>
    <div class="inner perechen">
    <?/*$APPLICATION->IncludeComponent("bitrix:breadcrumb","",Array(
            "START_FROM" => "1",
            "PATH" => "",
            "SITE_ID" => SITE_ID
        )
    );*/?>
    <?print_r($SECTION_HTML);?>
<?}?>