<?php

if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if(!isset($arParams["CACHE_TIME"])) {
    $arParams["CACHE_TIME"] = 36000000;
}

if(!isset($arParams['GROUP_BY'])) {
    $arParams['GROUP_BY'] = false;
}

$arSelect = (isset($arParams['SELECT']) && !empty($arParams['SELECT'])) ? $arParams['SELECT'] : array();
$arOrder = (isset($arParams['ORDER']) && !empty($arParams['ORDER'])) ? $arParams['ORDER'] : array('sort' => 'asc');
$arNavParams = (isset($arParams['ELEMENT_COUNT']) && !empty($arParams['ELEMENT_COUNT'])) ? array("nPageSize" => $arParams["ELEMENT_COUNT"]) : false;
$arParams["IBLOCK_ID"] = trim($arParams["IBLOCK_ID"]);

$arFilter = array('SITE_ID' => SITE_ID, 'IBLOCK_ID' => $arParams["IBLOCK_ID"], 'ACTIVE' => 'Y');
if(!empty($arParams['FILTER'])) {
    $arFilter = array_merge($arFilter, $arParams['FILTER']);
}

if($this->StartResultCache(false)) {
    if(!CModule::IncludeModule("iblock")) {
        $this->AbortResultCache();
        ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
        return;
    }

    $arResult['ITEMS'] = array();
    $arResult["ELEMENTS"] = array();
    $res = CIBlockElement::GetList($arOrder, $arFilter, $arParams['GROUP_BY'], $arNavParams, $arSelect);
    while($ob = $res->GetNextElement()) {
        $arFields = $ob->GetFields();
        $arProps = array();
        if($arParams['PROPS'] == 'Y') {
            $arFields['PROPERTIES'] = $ob->GetProperties();
        }

        $arFields['PREVIEW_PICTURE'] = array(
            'ID' => $arFields['PREVIEW_PICTURE'],
            'SRC' => CFile::GetPath($arFields['PREVIEW_PICTURE'])
        );
        $arFields['DETAIL_PICTURE'] = array(
            'ID' => $arFields['DETAIL_PICTURE'],
            'SRC' => CFile::GetPath($arFields['DETAIL_PICTURE'])
        );
        $arResult['ITEMS'][] = array_merge($arFields);
        $arResult["ELEMENTS"][] = $arFields['ID'];
    }

    $arResult["NAV_STRING"] = $res->GetPageNavStringEx($navComponentObject, '', '', 'N');
    if (is_object($navComponentObject) && method_exists($navComponentObject, "GetTemplateCachedData")) {
        $arResult["NAV_CACHED_DATA"] = $navComponentObject->GetTemplateCachedData();
    }
    $arResult["NAV_RESULT"] = $res;
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
