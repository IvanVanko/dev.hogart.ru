<?

global $APPLICATION;
if((!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) &&
        (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest')) die();
    else if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
        extract($_POST);
        $APPLICATION->RestartBuffer();
    }



$APPLICATION->IncludeComponent(
    "bitrix:form.result.new",
    "hogart_request",
    Array(
        "SEF_MODE" => "N",
        "WEB_FORM_ID" => $FORM_ID,
        "LIST_URL" => "",
        "EDIT_URL" => "",
        "SUCCESS_URL" => "",
        "CHAIN_ITEM_TEXT" => "",
        "CHAIN_ITEM_LINK" => "",
        "IGNORE_CUSTOM_TEMPLATE" => "N",
        "USE_EXTENDED_ERRORS" => "N",
        "CACHE_TYPE" => "N",
        "CACHE_TIME" => "0",
        "CACHE_NOTES" => "",
        "VARIABLE_ALIASES" => Array(
            "WEB_FORM_ID" => "WEB_FORM_ID",
            "RESULT_ID" => "RESULT_ID"
        ),

        "CUSTOM_INPUT_PARAMS" => array(
            "SEMINAR_USER_EMAIL" => array(
                "data-rule-email" => "true",
                "data-msg-email" => "Введите правильный email адрес"
            ),
        ),

        "CUSTOM_WRAPPER_PARAMS" => array(
            "SEMINAR_USER_EMAIL" => "data-clone-hidden=\"seminar_user_email\"",
            "SEMINAR_USER_PHONE" => "data-clone-hidden=\"seminar_user_phone\"",
            "SEMINAR_USER_CMP" => "data-clone-hidden=\"seminar_user_phone\"",
            "SEMINAR_USER_LNAME" => "data-clone=\"seminar_user_lname\"",
            "SEMINAR_USER_NAME" => "data-clone=\"seminar_user_name\"",
            "SEMINAR_USER_MNAME" => "data-clone=\"seminar_user_mname\"",
            "SEMINAR_USER_POST" => "data-clone=\"seminar_user_post\"",
            "SEMINAR_ID" => "data-clone=\"seminar_id\"",
            "SEMINAR_EAN_CODE" => "data-clone=\"seminar_ean_code\"",
            "SEMINAR_NAME" => "data-clone=\"seminar_name\"",
            "SEMINAR_ORG" => "data-clone=\"seminar_org\"",
            "SEMINAR_ADRESS" => "data-clone=\"seminar_adress\"",
            "SEMINAR_START" => "data-clone=\"seminar_start\"",
            "SEMINAR_REGISTRATION_NUMBER" => "data-clone=\"seminar_registration_number\"",
        ),

        "CUSTOM_WRAPPER_CSS" => array(
            "SEMINAR_USER_PHONE" => "field custom_label phone"
        ),

        "CUSTOM_REQUIRED_MESS" => array(
            "SEMINAR_USER_LNAME" => "Пожалуйста, введите фамилию"
        ),

        "CUSTOM_VALS" => $FORM_VALUES,
        "HIDE_INPUTS" => $HIDE_INPUTS,


        "CUSTOM_SUCCESS_URL" => "/learn/result.php",
        "HIDE_SUBMIT" => "Y",

        "SUCCESS_MESSAGE" => ""


    )
);
?>