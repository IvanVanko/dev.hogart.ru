<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$nav = array();
$arSelect = Array("ID", "NAME", 'DETAIL_PAGE_URL');
$arFilter = Array("IBLOCK_ID" => $arParams['IBLOCK_ID'], "ACTIVE" => "Y");
$res = CIBlockElement::GetList($arParams['ORDER'], $arFilter, false, Array("nElementID" => $arResult['ID'], 'nPageSize' => 1), $arSelect);
while($ob = $res->GetNextElement()) {
	$arFields = $ob->GetFields();
	$nav[] = $arFields;
}

if ($nav[0]['ID'] != $arParams['ID'])
	$arResult['PREV'] = $nav[0]['DETAIL_PAGE_URL'];

if ($nav[count($nav)-1]['ID'] != $arParams['ID'])
	$arResult['NEXT'] = $nav[count($nav)-1]['DETAIL_PAGE_URL'];