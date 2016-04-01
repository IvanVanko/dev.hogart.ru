<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arFilter = array(
	'IBLOCK_ID' => $arParams['IBLOCK_ID'],
	'ACTIVE'    => 'Y'
);

$arFilter = array_merge($arFilter, $arParams['FILTER']);
$arResult['ITEMS_COUNT'] = CIBlockElement::GetList(array(), $arFilter, array(), false, array());