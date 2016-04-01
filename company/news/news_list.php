<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>
<?
	$arFilter = array();
	$data = unserialize($_GET["filter"]);
	/*if (!empty($_REQUEST["tag"])) {
		foreach ($_REQUEST["tag"] as $tag)
			$arFilter["PROPERTY_tag_VALUE"][] = $tag;
	}*/
	$arFilter = $data;

	if (!empty($_REQUEST["brand"]))
		$arFilter["PROPERTY_brand"] = $_REQUEST["brand"];

	if (!empty($_REQUEST["catalog_section"]))
		$arFilter["PROPERTY_catalog_section"] = $_REQUEST["catalog_section"];

	if (!empty($_REQUEST["direction"]) && empty($_REQUEST['catalog_section'])) {
		$arFilterSections = array(
			'IBLOCK_ID'      => 1,
			'>=LEFT_BORDER'  => $_REQUEST['direction_'.$_REQUEST['direction'].'_left'],
			'<=RIGHT_BORDER' => $_REQUEST['direction_'.$_REQUEST['direction'].'_right'],
		);
		$rsSect = CIBlockSection::GetList(array('left_margin' => 'asc'),$arFilterSections);
		while ($arSect = $rsSect->GetNext()) {
		   $arFilter["PROPERTY_catalog_section"][] = $arSect["ID"];
		}
	}

	$APPLICATION->IncludeComponent(
		"kontora:element.list",
		"news-list-mobile-ajax",
		Array(
			"IBLOCK_ID"	    	=> 3,
			"SEF_FOLDER" 		=> "/company/news/",
			"PROPS"        		=> "Y",
			"NAV"          		=> "Y",
			"ELEMENT_COUNT" 	=> 10, 
			"FILTER"        		=> $arFilter,
			"ORDER"         		=> array("property_priority" => "asc", "active_from" => "desc")
		)
	);
?>
<?/*$APPLICATION->IncludeComponent(
	"bitrix:news", 
	"news", 
	array(
		"IBLOCK_ID" => "3",
		"SEF_MODE" => "Y",
		"SEF_FOLDER" => "/company/news/",
		"IBLOCK_TYPE" => "news",
		"NEWS_COUNT" => "20",
		"USE_SEARCH" => "N",
		"USE_RSS" => "N",
		"USE_RATING" => "N",
		"USE_CATEGORIES" => "N",
		"USE_FILTER" => "N",
		"SORT_BY1" => "property_priority",
		"SORT_ORDER1" => "asc",
		"SORT_BY2" => "active_from",
		"SORT_ORDER2" => "desc",
		"CHECK_DATES" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"CACHE_TYPE" => "N",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "Y",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN" => "Y",
		"ADD_ELEMENT_CHAIN" => "N",
		"USE_PERMISSIONS" => "N",
		"PREVIEW_TRUNCATE_LEN" => "",
		"LIST_ACTIVE_DATE_FORMAT" => "d.m.Y",
		"LIST_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"LIST_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"DISPLAY_NAME" => "Y",
		"META_KEYWORDS" => "-",
		"META_DESCRIPTION" => "-",
		"BROWSER_TITLE" => "-",
		"DETAIL_ACTIVE_DATE_FORMAT" => "d.m.Y",
		"DETAIL_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"DETAIL_PROPERTY_CODE" => array(
			0 => "tag",
			1 => "",
		),
		"DETAIL_DISPLAY_TOP_PAGER" => "N",
		"DETAIL_DISPLAY_BOTTOM_PAGER" => "Y",
		"DETAIL_PAGER_TITLE" => "Страница",
		"DETAIL_PAGER_TEMPLATE" => "",
		"DETAIL_PAGER_SHOW_ALL" => "Y",
		"PAGER_TEMPLATE" => ".default",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Новости",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"SEF_URL_TEMPLATES" => array(
			"news" => "/company/news/",
			"section" => "",
			"detail" => "#ELEMENT_CODE#/",
		)
	),
	false
);*/?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>