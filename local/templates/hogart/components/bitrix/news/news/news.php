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


if($APPLICATION->GetCurDir() === $arParams["SEF_FOLDER"]) {
    $this->setFrameMode(true);
    $arFilter = array();
    if(!empty($_REQUEST["tag"])) {
        foreach($_REQUEST["tag"] as $tag) {
            $arFilter["PROPERTY_tag_VALUE"][] = $tag;
        }
    }

    if(!empty($_REQUEST["brand"])) {
        $arFilter["PROPERTY_brand"] = $_REQUEST["brand"];
    }

    if(!empty($_REQUEST["catalog_section"])) {
        $arFilter["PROPERTY_catalog_section"] = $_REQUEST["catalog_section"];
    }

    if(!empty($_REQUEST["direction"])) {
        foreach($_REQUEST["direction"] as $section) {
            $arFilterSections = array(
                'IBLOCK_ID' => 1,
                '>=LEFT_BORDER' => $_REQUEST['direction_'.$section.'_left'],
                '<=RIGHT_BORDER' => $_REQUEST['direction_'.$section.'_right'],
            );
            $rsSect = CIBlockSection::GetList(array('left_margin' => 'asc'), $arFilterSections);
            while($arSect = $rsSect->GetNext()) {
                $arFilter["PROPERTY_catalog_section"][] = $arSect["ID"];
            }
        }
    }

    $APPLICATION->IncludeComponent(
        "kontora:element.list",
        "",
        Array(
            "IBLOCK_ID" => $arParams["IBLOCK_ID"],
            "PROPS" => "Y",
            "NAV" => "Y",
            "CHECK_PERMISSIONS" => "Y",
            "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
            "CACHE_TIME" => $arParams["CACHE_TIME"],
            "ELEMENT_COUNT" => 10,
            "FILTER" => $arFilter,
            "ORDER" => array($arParams["SORT_BY1"] => $arParams["SORT_ORDER1"],
                             $arParams["SORT_BY2"] => $arParams["SORT_ORDER2"]),
        ),
        $component
    );
}
else {
    BXHelper::NotFound();
}