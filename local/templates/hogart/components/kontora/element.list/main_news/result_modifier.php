<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)	die();?>
<?
$arResult['GROUPS'] = array_chunk($arResult['ITEMS'], 2);
BXHelper::addCachedKeys($this->__component, array('GROUPS'), $arResult);
?>