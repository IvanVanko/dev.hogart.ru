<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("COMMENTS_CONTROLLER_NAME"),
	"DESCRIPTION" => GetMessage("COMMENTS_CONTROLLER_DESCRIPTION"),
	"CACHE_PATH" => "Y",
	"PATH" => array(
		"ID" => "service",
		"CHILD" => array(
			"ID" => "comments",
			"NAME" => GetMessage("COMMENTS_LIST_SERVICE")
		)
	),
);
?>