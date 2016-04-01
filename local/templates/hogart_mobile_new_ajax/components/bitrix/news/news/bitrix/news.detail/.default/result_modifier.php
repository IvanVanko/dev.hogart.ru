<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

//Получить все Теги
/*$arResult['FILTER']['TAG'] = array();
$res = CIBlockElement::GetList(array("PROPERTYSORT_TAG" => "ASC"), $arFilter, array("PROPERTY_TAG_VALUE", "PROPERTY_TAG_ENUM_ID"), false, array());
while ($ob = $res->GetNextElement()) {
	$arFields = $ob->GetFields();
	$arResult['FILTER']['TAG'][] = $arFields;
}*/
# DebugMessage($arResult['FILTER']);

$nav = array();
$arSelect = Array("ID", "NAME", 'DETAIL_PAGE_URL');
$arFilter = Array("IBLOCK_ID" => $arParams['IBLOCK_ID'], "ACTIVE" => "Y");
$res = CIBlockElement::GetList(array($arParams['SORT_BY1'] => $arParams['SORT_ORDER1'],$arParams['SORT_BY2'] => $arParams['SORT_ORDER2'],), $arFilter, false, Array("nElementID" => $arParams['ID'], 'nPageSize' => 1), $arSelect);
while($ob = $res->GetNextElement()) {
	$arFields = $ob->GetFields();
	$nav[] = $arFields;
}

if ($nav[0]['ID'] != $arParams['ID'])
	$arResult['PREV'] = $nav[0]['DETAIL_PAGE_URL'];

if ($nav[count($nav)-1]['ID'] != $arParams['ID'])
	$arResult['NEXT'] = $nav[count($nav)-1]['DETAIL_PAGE_URL'];