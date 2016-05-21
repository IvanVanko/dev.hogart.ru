<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

global $APPLICATION;

$APPLICATION->AddChainItem(GetMessage("Реализованные проекты"), SITE_DIR . "integrated-solutions/");
$APPLICATION->AddChainItem($arResult['NAME']);