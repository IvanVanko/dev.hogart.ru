<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000000;

$arSelect = (isset($arParams['SELECT']) && !empty($arParams['SELECT'])) ? $arParams['SELECT'] : array();

if (!isset($arParams['CNT']))
	$arParams['CNT'] = false;

$arParams["SECTION_ID"] = trim($arParams["SECTION_ID"]);

$arFilter = array();

if (isset($arParams['SECTION_ID']) && !empty($arParams['SECTION_ID']))
	$arFilter['ID'] = $arParams["SECTION_ID"];

if (isset($arParams['SECTION_CODE']) && !empty($arParams['SECTION_CODE']))
	$arFilter['CODE'] = $arParams["SECTION_CODE"];

if (!empty($arParams['FILTER']))
	$arFilter = array_merge($arFilter, $arParams['FILTER']);

if ($this->StartResultCache(false)) {
	if (!CModule::IncludeModule("iblock")) {
		$this->AbortResultCache();
		ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
		return;
	}

	$rsSect = CIBlockSection::GetList(array(),$arFilter, $arParams['CNT'], $arSelect);
	if ($arSect = $rsSect->GetNext()) {
		$arSect['PICTURE'] = array(
			'ID'  => $arSect['PICTURE'],
			'SRC' => CFile::GetPath($arSect['PICTURE'])
		);
		$arSect['DETAIL_PICTURE'] = array(
			'ID'  => $arSect['DETAIL_PICTURE'],
			'SRC' => CFile::GetPath($arSect['DETAIL_PICTURE'])
		);
		$arResult = $arSect;
	}
	
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
if ($arParams["SET_TITLE"])
	$APPLICATION->SetTitle($arResult["NAME"]);

return $arResult["ID"];
?>