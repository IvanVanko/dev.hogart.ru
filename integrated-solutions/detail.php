<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Комплексные решения");
?>
<?
$APPLICATION->IncludeComponent(
    "bitrix:news.detail",
    "hogart_project_detail",
    Array(
        "DISPLAY_DATE" => "Y",
        "DISPLAY_NAME" => "Y",
        "DISPLAY_PICTURE" => "Y",
        "DISPLAY_PREVIEW_TEXT" => "Y",
        "ADD_SECTIONS_CHAIN" => "Y",
        "ADD_ELEMENT_CHAIN" => "Y",
        "CHECK_DATES" => "N",
        "CACHE_TYPE" => "N",
        "CACHE_TIME" => "3600",
        "CACHE_NOTES" => "",
        "IBLOCK_TYPE" => "solutions",
        "PROPERTY_CODE" => array(
            "prop_proj"
        ),
        "SEF_FOLDER" => "/integrated-solutions/",
        "IBLOCK_ID" => "18",
        //result_modifier.php
        "ORDER" => array('sort' => 'asc'),
        "ELEMENT_CODE" => $_REQUEST['ELEMENT_CODE']
    ),
    $component
);
?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>