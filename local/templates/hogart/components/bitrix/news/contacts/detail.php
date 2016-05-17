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

if (strlen($arResult["VARIABLES"]["ELEMENT_CODE"])) {
    $ElementID = $APPLICATION->IncludeComponent("kontora:element.detail", "", array(
            "IBLOCK_ID"  => $arParams["IBLOCK_ID"],
            "CODE"    => $arResult["VARIABLES"]["ELEMENT_CODE"],
            'PROPS' => 'Y',
            'SET_STATUS_404' => $arParams['SET_STATUS_404']
        ),
        $component
    );

    $APPLICATION->IncludeComponent(
        "kontora:element.list",
        "list",
        Array(
            "IBLOCK_ID"  => $arParams["IBLOCK_ID"],
            'ORDER'      => array('sort' => 'asc'),
            'CURRENT_ID' => $ElementID,
        ), $component
    );
} else {
    BXHelper::NotFound();
}