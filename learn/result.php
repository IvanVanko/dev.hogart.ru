<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("body_class", "reg_page");
$APPLICATION->SetTitle("Заявка на посещение семинара");
?>
<div class="inner">
    <?$APPLICATION->ShowViewContent('SEMINAR_PREVIEW_TEXT')?>
    <?$_REQUEST['set_filter'] = "Y";?>
    <?$APPLICATION->IncludeComponent(
        "bitrix:form.result.list",
        "hogart_seminar_result_list",
        array(
            "WEB_FORM_ID" => "5",
            "SEF_MODE" => "N",
            "SEF_FOLDER" => "/rezultat/",
            "VIEW_URL" => "result_view.php",
            "EDIT_URL" => "result_edit.php",
            "NEW_URL" => "result_new.php",
            "SHOW_ADDITIONAL" => "N",
            "SHOW_ANSWER_VALUE" => "N",
            "SHOW_STATUS" => "Y",
            "NOT_SHOW_FILTER" => array(
                0 => "",
                1 => "",
            ),
            "NOT_SHOW_TABLE" => array(
                0 => "",
                1 => "",
            ),
            "CHAIN_ITEM_TEXT" => "",
            "CHAIN_ITEM_LINK" => ""
        ),
        false
    );?>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>