<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Комплексные решения");
?>
<div class="row">
    <div class="col-md-9">
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
    </div>
    <div class="col-md-3 aside">
        <?
        $APPLICATION->IncludeComponent(
            "bitrix:form.result.new",
            "integrated-solutions-form",
            Array(
                "WEB_FORM_ID" => "MAKE_REQUEST_RU",
                "IGNORE_CUSTOM_TEMPLATE" => "N",
                "USE_EXTENDED_ERRORS" => "N",
                "SEF_MODE" => "N",
                "VARIABLE_ALIASES" => Array("WEB_FORM_ID"=>"WEB_FORM_ID","RESULT_ID"=>"RESULT_ID"),
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "3600",
                "LIST_URL" => "",
                "EDIT_URL" => "",
                "SUCCESS_URL" => "",
                "CHAIN_ITEM_TEXT" => "",
                "CHAIN_ITEM_LINK" => ""
            ), $component
        );
        ?>
    </div>
</div>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>