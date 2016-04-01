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
    $tpl = "";
    if (eregi("/contacts/", $APPLICATION->GetCurDir()) )
        $tpl = "contacts-page";
    $APPLICATION->IncludeComponent(
        "kontora:element.list",
        $tpl,
        Array(
            "IBLOCK_ID"	    => $arParams["IBLOCK_ID"],
            "PROPS"         => "Y",
            "ORDER"         => array('sort' => 'asc'),
        ),
        $component
    );
} else {
    BXHelper::NotFound();
}