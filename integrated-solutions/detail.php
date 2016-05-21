<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Комплексные решения");
?>
<div class="inner no-full">
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
<aside class="sidebar js-fh js-fixed-block js-paralax-height" data-fixed="top">
    <div class="inner js-paralax-item">
        <style>
            .preview-solutions-viewport{
                overflow: hidden;
            }
            .preview-solutions-viewport-inner{
                padding-right: 10%;
                width: 110%;
                overflow-y: scroll;
            }
        </style>
        <div class="padding">
            <div class="preview-solutions-viewport">
                <div class="preview-solutions-viewport-inner">
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
        </div>
        <script type="text/javascript">
            $(document).ready(function () {
                setTimeout(function () {
                    $(window).resize();
                }, 500);
            });
        </script>
        <?$APPLICATION->IncludeComponent("bitrix:main.include","",Array(
            "AREA_FILE_SHOW" => "page",
            "AREA_FILE_SUFFIX" => "inc_podpis",
            "AREA_FILE_RECURSIVE" => "Y",
            "EDIT_TEMPLATE" => "standard.php"
        ));?>
    </div>
</aside>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>