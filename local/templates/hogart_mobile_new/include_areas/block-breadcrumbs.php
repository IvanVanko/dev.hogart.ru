<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$APPLICATION->IncludeComponent("bitrix:menu", "mobile_breadcrumbs", Array(
                "ROOT_MENU_TYPE" => "mobile_breadcrumbs",
                "MAX_LEVEL" => "1",
                "CHILD_MENU_TYPE" => "mobile_breadcrumbs",
                "USE_EXT" => "N",
                "DELAY" => "Y",
                "ALLOW_MULTI_SELECT" => "N",
                "MENU_CACHE_TYPE" => "N",
                "MENU_CACHE_TIME" => "3600",
                "MENU_CACHE_USE_GROUPS" => "N",
                "MENU_CACHE_GET_VARS" => ""
            )
        );
?>