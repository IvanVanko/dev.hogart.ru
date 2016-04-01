<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

foreach ($arResult['ITEMS'] as $key => $arItem) {
	$res = CIBlockSection::GetByID($arItem['PROPERTIES']['solution_id']['VALUE']);
	if ($ar_res = $res->GetNext())
		$arResult['ITEMS'][$key]['SECTION_CODE'] = $ar_res['CODE'];
}