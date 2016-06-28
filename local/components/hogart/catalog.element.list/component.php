<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */
$this->setFrameMode(false);

if(!CModule::IncludeModule("iblock"))
{
    ShowError(GetMessage("CC_BIEAF_IBLOCK_MODULE_NOT_INSTALLED"));
    return;
}

$arNavParams = array(
    "nPageSize" => $arParams["PAGE_ELEMENT_COUNT"],
    "bDescPageNumbering" => $arParams["PAGER_DESC_NUMBERING"],
);
$arNavigation = CDBResult::GetNavParams($arNavParams);

if (empty($arParams["PAGER_PARAMS_NAME"]) || !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["PAGER_PARAMS_NAME"]))
{
    $pagerParameters = array();
}
else
{
    $pagerParameters = $GLOBALS[$arParams["PAGER_PARAMS_NAME"]];
    if (!is_array($pagerParameters))
        $pagerParameters = array();
}

$arrFilter = array_merge($GLOBALS[$arParams["FILTER_NAME"]], [

]);

$arSort = array(
    $arParams["ELEMENT_SORT_FIELD"] => $arParams["ELEMENT_SORT_ORDER"],
    $arParams["ELEMENT_SORT_FIELD2"] => $arParams["ELEMENT_SORT_ORDER2"],
);

$arSelect = array_merge([], $arParams['FIELDS_SELECT']);

if($this->startResultCache(false, array($arrFilter, ($arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups()), $arNavigation, $pagerParameters))) {

    if(!\Bitrix\Main\Loader::includeModule("iblock"))
    {
        $this->abortResultCache();
        ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
        return;
    }

    $rsElements = CIBlockElement::GetList($arSort, $arrFilter, false, $arNavParams, $arSelect);
    $arResult["ITEMS"] = [];
    while ($element = $rsElements->GetNextElement()) {
        $arResult["ITEMS"][] = array_merge($element->GetFields(), ["PROPERTIES" => $element->GetProperties()]);
    }

    $navComponentParameters = array();
    if ($arParams["PAGER_BASE_LINK_ENABLE"] === "Y")
    {
        $pagerBaseLink = trim($arParams["PAGER_BASE_LINK"]);
        if ($pagerBaseLink === "")
            $pagerBaseLink = $arResult["SECTION_PAGE_URL"];

        if ($pagerParameters && isset($pagerParameters["BASE_LINK"]))
        {
            $pagerBaseLink = $pagerParameters["BASE_LINK"];
            unset($pagerParameters["BASE_LINK"]);
        }

        $navComponentParameters["BASE_LINK"] = CHTTP::urlAddParams($pagerBaseLink, $pagerParameters, array("encode"=>true));
    }

    $arResult["NAV_STRING"] = $rsElements->GetPageNavStringEx(
        $navComponentObject,
        $arParams["PAGER_TITLE"],
        $arParams["PAGER_TEMPLATE"],
        $arParams["PAGER_SHOW_ALWAYS"],
        $this,
        $navComponentParameters
    );

    $strNavQueryString = ($navComponentObject->arResult["NavQueryString"] != "" ? $navComponentObject->arResult["NavQueryString"]."&amp;" : "");

    if ($navComponentObject->arResult["NavPageNomer"] - 1 > 0) {
        $arResult["PREV_LINK"] = $navComponentObject->arResult["sUrlPath"] . "?" . $strNavQueryString . "PAGEN_" . $navComponentObject->arResult["NavNum"] . "=" . ($navComponentObject->arResult["NavPageNomer"]-1);
    }

    if ($navComponentObject->arResult["NavPageNomer"] + 1 <= $navComponentObject->arResult["NavPageCount"]) {
        $arResult["NEXT_LINK"] = $navComponentObject->arResult["sUrlPath"] . "?" . $strNavQueryString . "PAGEN_" . $navComponentObject->arResult["NavNum"] . "=" . ($navComponentObject->arResult["NavPageNomer"]+1);
    }

    $this->setResultCacheKeys(array(
        "ID",
        "NAV_CACHED_DATA",
        $arParams["META_KEYWORDS"],
        $arParams["META_DESCRIPTION"],
        $arParams["BROWSER_TITLE"],
        $arParams["BACKGROUND_IMAGE"],
        "NAME",
        "PATH",
        "IBLOCK_SECTION_ID",
        "IPROPERTY_VALUES",
        "ITEMS_TIMESTAMP_X",
        'BACKGROUND_IMAGE'
    ));

    $this->includeComponentTemplate();
}