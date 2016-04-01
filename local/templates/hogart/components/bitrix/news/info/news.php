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

if ($APPLICATION->GetCurDir() === $arParams["SEF_FOLDER"]) {
    $arFilter = array();

    if (!empty($_REQUEST["brand"]))
        foreach ($_REQUEST["brand"] as $brand)
            $arFilter["PROPERTY_brand"][] = $brand;

    if (!empty($_REQUEST["direction"])) {
        foreach ($_REQUEST["direction"] as $direction) {
            if (!empty($_REQUEST['section_' . $direction])) {
                $arFilterSections = array(
                    'IBLOCK_ID' => 1,
                    '>=LEFT_BORDER' => $_REQUEST['section_' . $_REQUEST['section_' . $direction] . '_left'],
                    '<=RIGHT_BORDER' => $_REQUEST['section_' . $_REQUEST['section_' . $direction] . '_right'],
                );
            } else {
                $arFilterSections = array(
                    'IBLOCK_ID' => 1,
                    '>=LEFT_BORDER' => $_REQUEST['direction_' . $direction . '_left'],
                    '<=RIGHT_BORDER' => $_REQUEST['direction_' . $direction . '_right'],
                );
            }
            $rsSect = CIBlockSection::GetList(array('left_margin' => 'asc'), $arFilterSections);
            while ($arSect = $rsSect->GetNext()) {
                $arFilter["PROPERTY_direction"][] = $arSect["ID"];
            }
        }
    }

    if (!empty($_REQUEST['keyword']))
        $arFilter["%NAME"] = $_REQUEST['keyword'];

    $APPLICATION->IncludeComponent(
        "kontora:element.list",
        "",
        Array(
            "IBLOCK_ID"	=> $arParams["IBLOCK_ID"],

            "PROPS"     => "Y",
            "ORDER"     => array('active_from' => 'desc'),
            "FILTER"    => $arFilter,
        ),
        $component
    );
} else {
    BXHelper::NotFound();
}