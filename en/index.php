<? require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetPageProperty("title", "Хогарт - официальный сайт.");
$APPLICATION->SetPageProperty("keywords", "Хогарт - официальный сайт.");
$APPLICATION->SetPageProperty("description", "Хогарт - официальный сайт.");
$APPLICATION->SetTitle("Хогарт - официальный сайт.");
$APPLICATION->SetPageProperty("body_class", "index_page");

$APPLICATION->IncludeComponent("bitrix:menu","main",Array(
        "ROOT_MENU_TYPE" => "main", 
        "MAX_LEVEL" => "2", 
        "CHILD_MENU_TYPE" => "left", 
        "USE_EXT" => "Y",
        "DELAY" => "N",
        "ALLOW_MULTI_SELECT" => "Y",
        "MENU_CACHE_TYPE" => "N", 
        "MENU_CACHE_TIME" => "3600", 
        "MENU_CACHE_USE_GROUPS" => "Y", 
        "MENU_CACHE_GET_VARS" => "" 
    )
);?>
<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');?>
