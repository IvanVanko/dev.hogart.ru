<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000000;

$arSelect = (isset($arParams['SELECT']) && !empty($arParams['SELECT'])) ? $arParams['SELECT'] : array();
$arOrder = (isset($arParams['ORDER']) && !empty($arParams['ORDER'])) ? $arParams['ORDER'] : array('sort' => 'asc');
$arNavParams = (isset($arParams['ELEMENT_COUNT']) && !empty($arParams['ELEMENT_COUNT'])) ? array("nPageSize" => $arParams["ELEMENT_COUNT"]) : false;
if (!isset($arParams['CNT']))
	$arParams['CNT'] = false;

$arParams["IBLOCK_ID"] = trim($arParams["IBLOCK_ID"]);

$arFilter = array('IBLOCK_ID' => $arParams["IBLOCK_ID"], 'ACTIVE' => 'Y');
if ($arParams["INCLUDE_INACTIVE"]=="Y") unset($arFilter["ACTIVE"]);

if (!empty($arParams['FILTER']))
	$arFilter = array_merge($arFilter, $arParams['FILTER']);

if ($this->StartResultCache(false)) {
	if (!CModule::IncludeModule("iblock")) {
		$this->AbortResultCache();
		ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
		return;
	}

	$arResult['SECTIONS'] = array();
	$arResult["ELEMENTS"] = array();
	$rsSect = CIBlockSection::GetList($arOrder,$arFilter, $arParams['CNT'], $arSelect, $arNavParams);
	while ($arSect = $rsSect->GetNext()) {
		$arSect['PICTURE'] = array(
			'ID'  => $arSect['PICTURE'],
			'SRC' => CFile::GetPath($arSect['PICTURE'])
		);
		$arSect['DETAIL_PICTURE'] = array(
			'ID'  => $arSect['DETAIL_PICTURE'],
			'SRC' => CFile::GetPath($arSect['DETAIL_PICTURE'])
		);
		$arResult['SECTIONS'][] = $arSect;
		$arResult["ELEMENTS"][] = $arSect['ID'];
	}
	
	$arResult["NAV_STRING"] = $rsSect->GetPageNavStringEx($navComponentObject, '', '', 'N');
	$arResult["NAV_CACHED_DATA"] = $navComponentObject->GetTemplateCachedData();
	$arResult["NAV_RESULT"] = $rsSect;
	$this->SetResultCacheKeys(array(
		"ID",
		"IBLOCK_TYPE_ID",
		"DETAIL_PAGE_URL",
		"NAV_CACHED_DATA",
		"NAME",
		"SECTION",
		"ELEMENTS",
		"PROPERTIES",
		"PREVIEW_PICTURE"
	));
	$this->IncludeComponentTemplate();
	
	return $arResult["ELEMENTS"];
}
?>