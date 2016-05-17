<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000000;

$arSelect = (isset($arParams['SELECT']) && !empty($arParams['SELECT'])) ? $arParams['SELECT'] : array();
$arOrder = (isset($arParams['ORDER']) && !empty($arParams['ORDER'])) ? $arParams['ORDER'] : array('sort' => 'asc');

if (intval($arParams["ID"])) {
    $arFilter = array(
        'ID' => intval($arParams["ID"]),
    );
} else if (!empty($arParams["CODE"])) {
    $arFilter = array(
        'CODE' => $arParams["CODE"],
    );
}

if (isset($arParams["IBLOCK_ID"])) {
	$arFilter['IBLOCK_ID'] = $arParams["IBLOCK_ID"];
}

$arFilter["ACTIVE"] = "Y";

if (!empty($arParams['FILTER']))
	$arFilter = array_merge($arFilter, $arParams['FILTER']);


if ($this->StartResultCache(false)) {
	if (!CModule::IncludeModule("iblock")) {
		$this->AbortResultCache();
		ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
		return;
	}

	$res = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
	if ($ob = $res->GetNextElement()) {
		$arFields = $ob->GetFields();
		if ($arParams['PROPS'] == 'Y')
			$arFields['PROPERTIES'] = $ob->GetProperties();

		$arFields['PREVIEW_PICTURE'] = array(
			'ID'  => $arFields['PREVIEW_PICTURE'],
			'SRC' => CFile::GetPath($arFields['PREVIEW_PICTURE'])
		);
		$arFields['DETAIL_PICTURE'] = array(
			'ID'  => $arFields['DETAIL_PICTURE'],
			'SRC' => CFile::GetPath($arFields['DETAIL_PICTURE'])
		);
		$arResult = $arFields;

        if ($arParams["ADD_CHAIN_ITEM"] != "N") {
            $APPLICATION->AddChainItem(
                $arFields["NAME"]
            );
        }

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
	} else {
		$this->AbortResultCache();
		ShowError(GetMessage("T_NEWS_NEWS_NA"));
		@define("ERROR_404", "Y");
		if ($arParams["SET_STATUS_404"] === "Y") CHTTP::SetStatus("404 Not Found");
	}
}
if ($arParams["SET_TITLE"]) $APPLICATION->SetTitle($arResult["NAME"]);

return $arResult["ID"];
?>