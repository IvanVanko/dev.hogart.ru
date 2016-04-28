<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$APPLICATION->ShowHead();
?>
    <?$_REQUEST['set_filter'] = "Y";?>
    <?$APPLICATION->IncludeComponent(
        "bitrix:form.result.list",
        "hogart_seminar_result_list_print",
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

<script>
    window.print();
</script>