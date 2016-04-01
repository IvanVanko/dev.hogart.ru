<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>
<?
global $APPLICATION; 
$aMenuLinksDef = Array(
	Array(
		"Главная", 
		"/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"Каталог продукции", 
		"/catalog/", 
		Array(), 
		Array(), 
		"" 
	),	
);
if ($APPLICATION->GetCurDir() != "/catalog/")
{
	$aMenuLinksExt = $APPLICATION->IncludeComponent("madeon:menu.sections", "", array( 
			"IS_SEF" => "Y", 
			"SEF_BASE_URL" => "/catalog/", 
			"SECTION_PAGE_URL" => "#SECTION_CODE#/", 
			"DETAIL_PAGE_URL" => "#SECTION_CODE#/#ELEMENT_ID#/", 
			"IBLOCK_TYPE" => "catalog", 
			"IBLOCK_ID" => "1", 
			"DEPTH_LEVEL" => "4", 
			"CACHE_TYPE" => "N", 
			"CACHE_TIME" => "3600", 
			"INCLUDE_SUBSECTIONS" => "N" 
		), 
		false 
	); 
	if (count($aMenuLinksExt) > 0)
	{
		if (count($aMenuLinksExt) == 1)
			unset($aMenuLinksDef[0]);
		elseif (count($aMenuLinksExt) == 2)
		{
			unset($aMenuLinksDef[0]);
			unset($aMenuLinksDef[1]);
		}
		$aMenuLinks = array_merge($aMenuLinksDef, $aMenuLinksExt); 	
	}
	else
	{
	 	$aMenuLinks = $aMenuLinksDef;
	}
}
else
	$aMenuLinks = $aMenuLinksDef;

#$aMenuLinks = array_merge($aMenuLinksDef, $aMenuLinksExt); 
?>