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
$this->setFrameMode(true);?>
<?
?>
<div class="row">
    <div class="col-md-9">
        <h3><?$APPLICATION->ShowTitle()?></h3>
        <?$APPLICATION->IncludeComponent(
            "kontora:element.list",
            "",
            Array(
                "IBLOCK_ID"	    => $arParams["IBLOCK_ID"],
                "PROPS"         => "Y",
                "FILTER"        => $arFilter,
                "ORDER"         => array($arParams["SORT_BY1"] => $arParams["SORT_ORDER1"], $arParams["SORT_BY2"] => $arParams["SORT_ORDER2"]),
            ),
            $component
        );?>
        <? $APPLICATION->ShowViewContent("brands-letters") ?>
        <p>
            <?$APPLICATION->IncludeComponent("bitrix:main.include","",Array(
                    "AREA_FILE_SHOW" => "sect",
                    "AREA_FILE_SUFFIX" => "inc_brands",
                    "AREA_FILE_RECURSIVE" => "Y",
                    "EDIT_TEMPLATE" => "standard.php"
                )
            );?>
        </p>
        <? $APPLICATION->ShowViewContent("brands-list") ?>
    </div>
    <div class="col-md-3">
        <?$APPLICATION->IncludeComponent(
            "kontora:element.list",
            "aside",
            Array(
                "IBLOCK_ID"	    => $arParams["IBLOCK_ID"],
                "PROPS"         => "Y",
                "FILTER"        => $arFilter,
                "ORDER"         => array($arParams["SORT_BY1"] => $arParams["SORT_ORDER1"], $arParams["SORT_BY2"] => $arParams["SORT_ORDER2"]),
            ),
            $component
        );?>
    </div>
</div>
