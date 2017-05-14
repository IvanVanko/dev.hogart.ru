<?
global $APPLICATION;
global $isShared;
?>

<?
if (!$isShared) {
    $APPLICATION->IncludeComponent(
        "api:yashare",
        "",
        Array(
            "DATA_DESCRIPTION" => "",
            "DATA_IMAGE" => "",
            "DATA_TITLE" => "",
            "DATA_URL" => "",
            "LANG" => "ru",
            "QUICKSERVICES" => array("vkontakte","facebook","odnoklassniki","twitter"),
            "SHARE_SERVICES" => array(),
            "SIZE" => "m",
            "TYPE" => $TYPE ? : "counter",
            "UNUSED_CSS" => "N",
            "twitter_hashtags" => ""
        )
    );
    $isShared = true;
}
?>