<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000000;


$arParams["SECTION_ID"] = trim($arParams["SECTION_ID"]);

$arFilter = array();

if (isset($arParams['SECTION_ID']) && !empty($arParams['SECTION_ID']))
	$arFilter['ID'] = $arParams["SECTION_ID"];
elseif (isset($arParams['SECTION_CODE']) && !empty($arParams['SECTION_CODE']))
	$arFilter['CODE'] = $arParams["SECTION_CODE"];

if (!empty($arParams['FILTER']))
	$arFilter = array_merge($arFilter, $arParams['FILTER']);

if ($this->StartResultCache(false)) {
	if (!CModule::IncludeModule("iblock")) {
		$this->AbortResultCache();
		ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
		return;
	}

print_r($arParams);
	
	$this->SetResultCacheKeys(array(
		"ID",
		"IBLOCK_TYPE_ID",
		"DETAIL_PAGE_URL",
		"NAME",
		"SECTION",
		"ELEMENTS",
		"PROPERTIES",
		"PREVIEW_PICTURE"
	));
	$this->IncludeComponentTemplate();
}


?>