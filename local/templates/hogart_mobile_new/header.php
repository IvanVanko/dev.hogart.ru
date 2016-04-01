<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<!DOCTYPE html>
<html>
<head>
	<?$APPLICATION->ShowHead()?>
	<title><?$APPLICATION->ShowTitle()?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">  
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<link href="<?=MOBILE_PATH?>css/all.css" rel="stylesheet" media="all">
	<script src="http://maps.googleapis.com/maps/api/js"></script>
	<script src="<?=MOBILE_PATH?>js/jquery.min.js"></script>
	<script src="<?=MOBILE_PATH?>js/owl.carousel.min.js"></script>
	<script src="<?=MOBILE_PATH?>js/jquery.touchSwipe.min.js"></script>
	<script src="<?=MOBILE_PATH?>js/jquery.inputmask.bundle.js"></script>
	<script src="<?=MOBILE_PATH?>js/markerwithlabel.js"></script> 
	<script src="<?=MOBILE_PATH?>js/jquery-ui.min.js"></script>  
	<script src="<?=MOBILE_PATH?>js/jquery.ui.touch-punch.dk.js"></script>
	<script src="<?=MOBILE_PATH?>js/jquery.magnific-popup.min.js"></script>
	<script src="<?=MOBILE_PATH?>js/jquery.validate.min.js"></script>
	<script src="<?=MOBILE_PATH?>js/main.js"></script>  
	<?$APPLICATION->ShowHeadScripts()?>	
</head>
<body>
<?$APPLICATION->ShowPanel();?>
<?
global $USER;
#$USER->Authorize(6); // авторизуем
$CurPage = $APPLICATION->GetCurPage();
$CurDir = $APPLICATION->GetCurDir();
$sub_class = "main-page";
$arPage = explode("/", $CurPage);

if ($CurDir == "/")
	$sub_class = "main-page";
if (eregi("/company/", $CurDir))
{
	$sub_class = "one-news-page";
}
if  (eregi("/company/news/", $CurDir))
{
	$sub_class = "news-page";
	if (strlen($arPage[3]) > 0 && $arPage[3] != "index.php")
	{
		$sub_class = "one-news-page";
	}
}
if  (eregi("/company/jobs/", $CurDir))
{
	$sub_class = "jobs";
}
elseif (eregi("/company/history.php", $CurPage))
{
	$sub_class = "contacts-page";

}
elseif (eregi("/contacts/", $CurDir))
{
	$sub_class = "contacts-page";
}
elseif (eregi("/catalog/", $CurDir))
{
	$sub_class = "catalog-page";
	if (strlen($arPage[2]) > 0 && $arPage[7] == "index.php")
	{
		$sub_class = "detail";
	}
}
elseif (eregi("/history/", $CurDir))
{
	$sub_class = "jobs";
}

elseif (eregi("/brands/", $CurDir))
{
	$sub_class = "brands";
	if (strlen($arPage[2]) > 0 && $arPage[3] == "index.php")
	{
		$sub_class = "brands-item";
	}
}
elseif (eregi("/stock/", $CurDir))
{
	$sub_class = "actions-page";
	if (strlen($arPage[3]) > 0 && $arPage[3] != "index.php")
	{
		$sub_class = "one-actions-page";
	}
}
elseif (eregi("/helpful-information/", $CurDir))
{
	$sub_class = "usefull-info";
	if (strlen($arPage[2]) > 0 && $arPage[3] == "index.php")
	{
		$sub_class = "usefull-info-one";
	}
}
elseif (eregi("/reg-info/", $CurDir))
{
	$sub_class = "registration";
}
elseif (eregi("search_all.php", $CurPage) || eregi("search_catalog.php", $CurPage) || eregi("search_docum.php", $CurPage) || eregi("/search-mobile/", $CurDir))
{
	$sub_class = "search";	
}
elseif (eregi("/selection-equipment/", $CurDir))
{
	$sub_class = "equipment";
}
elseif (eregi("/documentation/", $CurDir))
{
	$sub_class = "documents";
}
elseif (eregi("services/", $CurDir))
{
	$sub_class = "service";
}
elseif (eregi("/learning/", $CurDir))
{
	$sub_class = "learn-page";
	#DebugMessage($arPage);
	if (strlen($arPage[2]) > 0 && $arPage[3] == "index.php")
	{
		$sub_class = "learn-item-page";
		#DebugMessage($sub_class);
	}
}

?>
<div class="wrap <?=$sub_class?>">
	<?$APPLICATION->IncludeFile(
		INCLUDE_AREAS."block-right-panel.php",
		Array(),
		Array("MODE"=>"html", "NAME"=>"Right Panel")
	);?>
	<!-- Меню на главной -->
	<section class="slide " id="main_slide">
	<?/*$APPLICATION->IncludeComponent(
		"bitrix:breadcrumb",
		"hogart-breadcrumb",
		Array(
			"START_FROM" => "0", 
			"PATH" => "", 
			"SITE_ID" => SITE_ID 
		)
	);*/?>
	<?$APPLICATION->IncludeFile(
		INCLUDE_AREAS."block-breadcrumbs.php",
		Array(),
		Array("MODE"=>"html", "NAME"=>"Breadcrumbs")
	);?>
	<?$APPLICATION->IncludeFile(
		INCLUDE_AREAS."block-mainpage.php",
		Array(),
		Array("MODE"=>"html", "NAME"=>"Mainpage")
	);?>

