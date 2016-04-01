<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
Global $CurPage, $CurDir;
$page = "#SITE_DIR#search-mobile/search_all.php";
/*
if (eregi("/catalog/", $CurDir))
{
	$page = "#SITE_DIR#search/index_catalog.php";
}
if (eregi("/documentation/", $CurDir))
{
	$page = "#SITE_DIR#search/index_docum.php";
}*/
$APPLICATION->IncludeComponent("bitrix:search.form", "header-mobile", Array(
	"USE_SUGGEST" => "N",
	"PAGE" => $page,
	)
);
?>