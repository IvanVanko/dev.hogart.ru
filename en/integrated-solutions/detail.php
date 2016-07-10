<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Solutions");
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
        "CHECK_DATES" => "N",
        "IBLOCK_TYPE" => "solutions",
        "PROPERTY_CODE" => array(
            "prop_proj"
        ),
        "SEF_FOLDER" => "/en/integrated-solutions/",
        "IBLOCK_ID" => 37,
        "ORDER" => array('sort' => 'asc'),
        "ELEMENT_CODE" => $_REQUEST['ELEMENT_CODE']
    )
);
?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>