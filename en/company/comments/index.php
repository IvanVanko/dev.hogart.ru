<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Reviews");

$dbProps = CIBlockProperty::GetList([], ['IBLOCK_ID' => REVIEWS_IBLOCK_ID]);
while($prop = $dbProps->GetNext()) {
    $props[$prop['CODE']] = $prop;
}

$APPLICATION->IncludeComponent(
    "kontora:element.list",
    "comments",
    Array(
        "IBLOCK_ID" => REVIEWS_IBLOCK_ID,
        'ORDER' => array('sort' => 'asc'),
        'PROPS' => 'Y',
        "NAV" => "Y",
        "ELEMENT_COUNT" => 10,
    ));

$APPLICATION->IncludeComponent("bitrix:iblock.element.add.form", "comment", Array(
        "SEF_MODE" => "Y",
        "IBLOCK_TYPE" => "comments",
        "IBLOCK_ID" => REVIEWS_IBLOCK_ID,
        "PROPERTY_CODES" => array("NAME",
                                  $props['name']['ID'],
                                  $props['surname']['ID'],
                                  $props['post']['ID'],
                                  $props['mail']['ID'],
                                  "PREVIEW_TEXT",
                                  "PREVIEW_PICTURE"),
        "PROPERTY_CODES_REQUIRED" => array("NAME",
                                           $props['name']['ID'],
                                           $props['post']['ID'],
                                           $props['mail']['ID'],
                                           "PREVIEW_TEXT"),
        "GROUPS" => array("2"),
        "STATUS_NEW" => "2",
        "STATUS" => array("2"),
        "LIST_URL" => "",
        "ELEMENT_ASSOC" => "PROPERTY_ID",
        "ELEMENT_ASSOC_PROPERTY" => "",
        "MAX_USER_ENTRIES" => "100000",
        "MAX_LEVELS" => "100000",
        "LEVEL_LAST" => "Y",
        "USE_CAPTCHA" => "N",
        "USER_MESSAGE_EDIT" => "",
        "USER_MESSAGE_ADD" => "",
        "DEFAULT_INPUT_SIZE" => "30",
        "RESIZE_IMAGES" => "Y",
        "MAX_FILE_SIZE" => "0",
        "PREVIEW_TEXT_USE_HTML_EDITOR" => "N",
        "DETAIL_TEXT_USE_HTML_EDITOR" => "N",
        "CUSTOM_TITLE_NAME" => "Surname",
        "CUSTOM_TITLE_TAGS" => "",
        "CUSTOM_TITLE_DATE_ACTIVE_FROM" => "",
        "CUSTOM_TITLE_DATE_ACTIVE_TO" => "",
        "CUSTOM_TITLE_IBLOCK_SECTION" => "",
        "CUSTOM_TITLE_PREVIEW_TEXT" => "Message",
        "CUSTOM_TITLE_PREVIEW_PICTURE" => "",
        "CUSTOM_TITLE_DETAIL_TEXT" => "",
        "CUSTOM_TITLE_DETAIL_PICTURE" => "",
        "SEF_FOLDER" => "/",
        "VARIABLE_ALIASES" => Array()
    )
); ?>
<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>