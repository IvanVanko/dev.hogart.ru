<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

foreach ($arResult['ITEMS'] as $key => $arItem) {
	$arDirection = array();
	$direction = (is_array($arItem['PROPERTIES']['direction']['VALUE'])) ? $arItem['PROPERTIES']['direction']['VALUE'][0] : $arItem['PROPERTIES']['direction']['VALUE'];
	if (!empty($direction)) {
		$res = CIBlockSection::GetByID($direction);
		if ($ar_res = $res->GetNext())
		 	$arDirection = $ar_res;

		$arFilter = array(
			"IBLOCK_ID"      => 1,
			'<=LEFT_BORDER'  => $arDirection['LEFT_MARGIN'], 
			'>=RIGHT_BORDER' => $arDirection['RIGHT_MARGIN'],
		);
		$rsSect = CIBlockSection::GetList(array("left_margin" => "asc"), $arFilter);
		$firstSectionId = 0;
		while ($arSect = $rsSect->GetNext()) {
			$arResult['ITEMS'][$key]['BREADCRUMBS'][] = $arSect;
		}

		$arResult['ITEMS'][$key]['BREADCRUMBS'][] = $arDirection;
	} else {
		$arResult['ITEMS'][$key]['BREADCRUMBS'] = array();
	}
}

$arFilter = array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ACTIVE' => 'Y');
$arFilter = array_merge($arFilter, $arParams['FILTER']);
$arResult['ELEMENTS_COUNT'] = CIBlockElement::GetList(array(), $arFilter, array(), false, $arParams['SELECT']);

$this->SetViewTarget("doc_tab_cnt");
echo $arResult['ELEMENTS_COUNT'];
$this->EndViewTarget();?>