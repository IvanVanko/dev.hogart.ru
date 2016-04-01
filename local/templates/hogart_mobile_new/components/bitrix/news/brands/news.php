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
if ($APPLICATION->GetCurDir() === $arParams["SEF_FOLDER"]) {?>
<div class="brand-wrap">
<?$APPLICATION->IncludeComponent("bitrix:main.include","",Array(
	"AREA_FILE_SHOW" => "sect",
	"AREA_FILE_SUFFIX" => "inc_brands",
	"AREA_FILE_RECURSIVE" => "Y",
	"EDIT_TEMPLATE" => "standard.php"
	)
);?>
		
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
</div>

	<?/*$APPLICATION->IncludeComponent("kontora:element.detail", "brands", array(
		"ID"    => "21",
		"PROPS" => "Y",
		"ADD_CHAIN_ITEM" => "N"
	));*/?>
<?
} else {
	BXHelper::NotFound();
}
?>

