<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?

#unset($APPLICATION->arAdditionalChain);
#DebugMessage($APPLICATION->arAdditionalChain[1]);
#$APPLICATION->AddChainItem("Главная", "/");
#$APPLICATION->AddChainItem("Контакты", "/contacts/");
?>