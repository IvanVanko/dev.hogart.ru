<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

$arFilter = array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ACTIVE' => 'Y');
$arFilter = array_merge($arFilter, $arParams['FILTER']);
$arResult['ELEMENTS_COUNT'] = CIBlockElement::GetList(array(), $arFilter, array(), false, $arParams['SELECT']);

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
    $brand_id = $arItem['PROPERTIES']['brand']['VALUE'];
    $brand_code = $arResult["BRAND_NAME"][$brand_id];
    if (!empty($brand_code)) {
        $exploded_url = explode("/",$arItem['DETAIL_PAGE_URL']);
        $c = count($exploded_url);
        array_splice( $exploded_url, $c-2, 0, array($brand_code) );
        $arItem['DETAIL_PAGE_URL'] = implode("/",$exploded_url);
    }
}

$this->SetViewTarget("catalog_tab_cnt");
echo $arResult['ELEMENTS_COUNT'];
$this->EndViewTarget();?>