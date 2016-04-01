<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

$arFilter = array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ACTIVE' => 'Y');
$arFilter = array_merge($arFilter, $arParams['FILTER']);
$CACHE_LIFE_TIME = 3600;

$ob_cache_prop = new CPHPCache();
$cache_id_prop = "props_search_ids".md5($arFilter);

if($ob_cache_prop->InitCache($CACHE_LIFE_TIME, $cache_id_prop, "/")) {

    $props = $ob_cache_prop->GetVars();
    $arResult['ELEMENTS_COUNT'] = $props['ids'];
}
else {
    $arResult['ELEMENTS_COUNT'] = CIBlockElement::GetList(array(), $arFilter, array(), false, $arParams['SELECT']);

    if($ob_cache_prop->StartDataCache()) {
        $ob_cache_prop->EndDataCache(array('ids' => $ids));
    }
}

$arFilter = array(
    "IBLOCK_ID"     => 2,
    "ACTIVE"        => "Y"
);
$arSelect = Array("ID", "NAME", "CODE");
$res = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
while ($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields();
    $arResult["BRAND_NAME"][$arFields['ID']] = $arFields['CODE'];
}

foreach ($arResult['ITEMS'] as &$arItem) {
    $arItem['PRICE'] = BXHelper::calculateDicountPrice($arItem, 1, false, SITE_ID, $arItem['CATALOG_CURRENCY_1']);
    $brand_code = "";
    $res = CIBlockElement::GetProperty(CATALOG_IBLOCK_ID, $arItem['ID'], "sort", "asc", array("CODE" => "brand"));
    if ($ob = $res->GetNext()){
        $brand_id = $ob['VALUE'];
        $brand_code = $arResult["BRAND_NAME"][$brand_id];
    }

    if (!empty($brand_code)) {
        $exploded_url = explode("/",$arItem['DETAIL_PAGE_URL']);
        $c = count($exploded_url);
        array_splice( $exploded_url, $c-2, 0, array($brand_code) );
        $arItem['DETAIL_PAGE_URL'] = implode("/",$exploded_url);
    }
}

$this->SetViewTarget("catalog_tab_cnt");
echo $arResult['ELEMENTS_COUNT'];
$this->EndViewTarget();