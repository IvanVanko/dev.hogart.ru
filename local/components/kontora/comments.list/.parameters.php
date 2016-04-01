<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/*
Пример использования поля Фильтр
$arParams['FILTER'] = array('ID' => 1, 'ELEMENT_ID' => 36, 'USER_ID' => 2);
*/
$arComponentParameters = array(
	"GROUPS" => array(
	),
	"PARAMETERS" => array(
		"FILTER" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("PARAM_FILTER"),
			"TYPE" => "STRING",
		),
		"ONE_LEVEL" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("PARAM_ONE_LEVEL"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
		"USE_RATING" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("PARAM_USE_RATING"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
		"USE_LIKES" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("PARAM_USE_LIKES"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
		"USE_VOTE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("PARAM_USE_VOTE"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
		"SHOW_USER_PHOTO" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("PARAM_SHOW_USER_PHOTO"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
		"CACHE_TIME"  =>  array("DEFAULT"=>36000000),
	),
);
?>