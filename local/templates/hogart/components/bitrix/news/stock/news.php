<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

if($APPLICATION->GetCurDir() === $arParams["SEF_FOLDER"]) {
    $arFilter = array();

    if(!empty($_REQUEST["brand"])) {
        foreach($_REQUEST["brand"] as $brand) {
            $arFilter["PROPERTY_brand"][] = $brand;
        }
    }

    if(!empty($_REQUEST["direction"])) {
        foreach($_REQUEST["direction"] as $section) {
            $arFilter["PROPERTY_catalog_section"][] = $section;
        }
    }

    if(!empty($_REQUEST["sale"])) {
        $arFilter["PROPERTY_sale_VALUE"] = $_REQUEST["sale"];
    }

    if(!empty($_REQUEST["markdown"])) {
        $arFilter["PROPERTY_markdown_VALUE"] = $_REQUEST["markdown"];
    }

    if(!empty($_REQUEST["city"])) {
        $arFilter["PROPERTY_city"] = $_REQUEST["city"];
    }
    
    // не показываем в списке акции, у которых стоит флаг с запретом
    $arFilter['!PROPERTY_NOT_SHOW_IN_LIST'] = "Y";

    $arrID = $APPLICATION->IncludeComponent(
        "kontora:element.list",
        "",
        Array(
            "IBLOCK_ID" => $arParams["IBLOCK_ID"],
            "PROPS" => "Y",
            "CHECK_PERMISSIONS" => "Y",
            "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
            "CACHE_TIME" => $arParams["CACHE_TIME"],
            "ORDER" => array($arParams["SORT_BY1"] => $arParams["SORT_ORDER1"],
                             $arParams["SORT_BY2"] => $arParams["SORT_ORDER2"]),
            "FILTER" => $arFilter,
        ),
        $component
    );
}
else {
    BXHelper::NotFound();
} ?>

