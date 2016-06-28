<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); 

global $APPLICATION; 

$aMenuLinksExt=$APPLICATION->IncludeComponent("bitrix:menu.sections", "", array( 
		"IS_SEF" => "Y", 
		"SEF_BASE_URL" => "/catalog/", 
		"SECTION_PAGE_URL" => "#bx_cat_#SECTION_ID#",
		"DETAIL_PAGE_URL" => "#SECTION_CODE#/#ELEMENT_ID#/", 
		"IBLOCK_TYPE" => "catalog", 
		"IBLOCK_ID" => "1", 
		"DEPTH_LEVEL" => "1", 
		"CACHE_TYPE" => "A", 
		"CACHE_TIME" => "3600", 
		"INCLUDE_SUBSECTIONS" => "Y" 
	), 
	false 
); 

$aMenuLinks = array_merge($aMenuLinksExt, $aMenuLinks); 

?>