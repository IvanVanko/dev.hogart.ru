<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$APPLICATION->IncludeComponent("kontora:comments.controller", "", array(
		"ELEMENT_ID" => $arParams["FILTER"]["ELEMENT_ID"],
	),
	$component
);

$APPLICATION->IncludeComponent("kontora:comments.list", "", array(
		"FILTER"          => $arParams["FILTER"],
		"ONE_LEVEL"       => $arParams["ONE_LEVEL"],
		"USE_RATING"      => $arParams["USE_RATING"],
		"USE_LIKES"       => $arParams["USE_LIKES"],
		"USE_VOTE"        => $arParams["USE_VOTE"],
		"SHOW_USER_PHOTO" => $arParams["SHOW_USER_PHOTO"],
		"CACHE_TYPE"      => $arParams["CACHE_TYPE"],
		"CACHE_TIME"      => $arParams["CACHE_TIME"]
	),
	$component
);

$APPLICATION->IncludeComponent("kontora:comments.addform", "", array(), $component);

?>