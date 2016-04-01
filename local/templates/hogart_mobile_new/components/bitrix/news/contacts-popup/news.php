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
    $APPLICATION->IncludeComponent(
        "kontora:element.list",
        "",
        Array(
            "IBLOCK_ID"     => $arParams["IBLOCK_ID"],
            "PROPS"         => "Y",
            "NAV"           => "Y",
            "ELEMENT_COUNT" => 10, 
            #"FILTER"        => $arFilter,
            "ORDER"         => array($arParams['SORT_BY1'] => $arParams['SORT_ORDER1'], $arParams['SORT_BY2'] => $arParams['SORT_ORDER2'])
        ),
        $component
    );
