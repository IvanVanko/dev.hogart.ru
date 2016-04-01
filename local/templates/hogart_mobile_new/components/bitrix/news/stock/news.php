<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
#DebugMessage( $arParams);
if ($APPLICATION->GetCurDir() === $arParams["SEF_FOLDER"]) {
    $arFilter = array();

    if (!empty($_REQUEST["brand"]))
        foreach ($_REQUEST["brand"] as $brand)
            $arFilter["PROPERTY_brand"][] = $brand;

    if (!empty($_REQUEST["direction"]))
        foreach ($_REQUEST["direction"] as $section)
            $arFilter["PROPERTY_catalog_section"][] = $section;

    if (!empty($_REQUEST["sale"]))
        $arFilter["PROPERTY_sale_VALUE"] = $_REQUEST["sale"];

    if (!empty($_REQUEST["markdown"]))
        $arFilter["PROPERTY_markdown_VALUE"] = $_REQUEST["markdown"];

    if (!empty($_REQUEST["city"]))
        $arFilter["PROPERTY_city"] = $_REQUEST["city"];

    $arrID = $APPLICATION->IncludeComponent(
        "kontora:element.list",
        "",
        Array(
            "IBLOCK_ID"	    => $arParams["IBLOCK_ID"],
            "PROPS"         => "Y",
            "FILTER"        => $arFilter,
        ),
        $component
    );
} else {
    BXHelper::NotFound();
}?>

