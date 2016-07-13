<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Отзывы");

$dbProps = CIBlockProperty::GetList([], ['IBLOCK_ID' => 15]);
while($prop = $dbProps->GetNext()) {
    $props[$prop['CODE']] = $prop;
}
?>
<div class="row">
    <?
    $APPLICATION->IncludeComponent(
        "kontora:element.list",
        "comments",
        Array(
            "IBLOCK_ID" => 15,
            'ORDER' => array('sort' => 'asc'),
            'PROPS' => 'Y',
            "NAV" => "Y",
            "ELEMENT_COUNT" => 10,
        ));

    $APPLICATION->IncludeComponent("bitrix:iblock.element.add.form", "comment", Array(
            "SEF_MODE" => "Y",
            "IBLOCK_TYPE" => "comments",
            "IBLOCK_ID" => "15",
            "PROPERTY_CODES" => array("NAME",
                $props['name']['ID'],
                $props['surname']['ID'],
                $props['post']['ID'],
                $props['mail']['ID'],
                "PREVIEW_TEXT"),
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
            "CUSTOM_TITLE_NAME" => "Фамилия",
            "CUSTOM_TITLE_TAGS" => "",
            "CUSTOM_TITLE_DATE_ACTIVE_FROM" => "",
            "CUSTOM_TITLE_DATE_ACTIVE_TO" => "",
            "CUSTOM_TITLE_IBLOCK_SECTION" => "",
            "CUSTOM_TITLE_PREVIEW_TEXT" => "Отзыв",
            "CUSTOM_TITLE_PREVIEW_PICTURE" => "",
            "CUSTOM_TITLE_DETAIL_TEXT" => "",
            "CUSTOM_TITLE_DETAIL_PICTURE" => "",
            "SEF_FOLDER" => "/",
            "VARIABLE_ALIASES" => Array()
        )
    ); ?>
</div>

<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>