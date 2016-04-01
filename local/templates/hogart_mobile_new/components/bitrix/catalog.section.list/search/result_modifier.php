<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
foreach ($arResult['SECTIONS'] as $key => $arSection) {
	$arFilter = array(
		"IBLOCK_ID"           => $arParams['IBLOCK_ID'],
		"ACTIVE"              => "Y",
		"INCLUDE_SUBSECTIONS" => "Y",
		'SECTION_ID'          => $arSection['ID']
	);

	if (!empty($arParams['FILTER']))
		$arFilter = array_merge($arFilter, $arParams['FILTER']);

	$arResult['SECTIONS'][$key]['ELEMENT_CNT'] = CIBlockElement::GetList(array(), $arFilter, array(), false, array());
}