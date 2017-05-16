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
$SECTION_ID = (string)$_REQUEST['SECTION_ID'];
if (empty($SECTION_ID)) {
    $SECTION = CIBlockSection::GetList(["SORT"=>"ASC"], [
        "IBLOCK_ID" => CATALOG_IBLOCK_ID,
        "GLOBAL_ACTIVE"=>"Y",
        "IBLOCK_ACTIVE"=>"Y",
        "<=DEPTH_LEVEL" => 1
    ], false, ['ID'], ['nTopCount' => 1])->Fetch();
    LocalRedirect("?SECTION_ID=" . $SECTION['ID']);
    exit(0);
}

?>

<div class="row catalog-hide">
	<!-- блок категорий -->
    <?
    $APPLICATION->IncludeComponent(
        "bitrix:catalog.section.list",
        "",
        array(
            "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
            "IBLOCK_ID" => $arParams["IBLOCK_ID"],
            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
            "CACHE_TIME" => $arParams["CACHE_TIME"],
            "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
            "COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
            "TOP_DEPTH" => $arParams["SECTION_TOP_DEPTH"],
            "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
            "SECTION_USER_FIELDS" => ["UF_*"],
            "VIEW_MODE" => $arParams["SECTIONS_VIEW_MODE"],
            "SHOW_PARENT_NAME" => $arParams["SECTIONS_SHOW_PARENT_NAME"],
            "HIDE_SECTION_NAME" => (isset($arParams["SECTIONS_HIDE_SECTION_NAME"]) ? $arParams["SECTIONS_HIDE_SECTION_NAME"] : "N"),
            "ADD_SECTIONS_CHAIN" => (isset($arParams["ADD_SECTIONS_CHAIN"]) ? $arParams["ADD_SECTIONS_CHAIN"] : ''),
            "SECTION_ID" => $SECTION_ID,
        ),
        $component,
        array("HIDE_ICONS" => "Y")
    );
    ?>
</div>
<!-- блок категорий для мобильной версии -->
<div class="container-main">
    <?
    $APPLICATION->IncludeComponent(
        "hogart:catalog.section.list",
        "mobile",
        array(
            "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
            "IBLOCK_ID" => $arParams["IBLOCK_ID"],
            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
            "CACHE_TIME" => $arParams["CACHE_TIME"],
            "TOP_DEPTH" => $arParams["SECTION_TOP_DEPTH"],
        ),
        $component,
        array("HIDE_ICONS" => "Y")
    );
    ?>
</div>