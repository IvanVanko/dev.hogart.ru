<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (isset($arParams["TEMPLATE_THEME"]) && !empty($arParams["TEMPLATE_THEME"]))
{
	$arAvailableThemes = array();
	$dir = trim(preg_replace("'[\\\\/]+'", "/", dirname(__FILE__)."/themes/"));
	if (is_dir($dir) && $directory = opendir($dir))
	{
		while (($file = readdir($directory)) !== false)
		{
			if ($file != "." && $file != ".." && is_dir($dir.$file))
				$arAvailableThemes[] = $file;
		}
		closedir($directory);
	}

	if ($arParams["TEMPLATE_THEME"] == "site")
	{
		$solution = COption::GetOptionString("main", "wizard_solution", "", SITE_ID);
		if ($solution == "eshop")
		{
			$theme = COption::GetOptionString("main", "wizard_eshop_adapt_theme_id", "blue", SITE_ID);
			$arParams["TEMPLATE_THEME"] = (in_array($theme, $arAvailableThemes)) ? $theme : "blue";
		}
	}
	else
	{
		$arParams["TEMPLATE_THEME"] = (in_array($arParams["TEMPLATE_THEME"], $arAvailableThemes)) ? $arParams["TEMPLATE_THEME"] : "blue";
	}
}
else
{
	$arParams["TEMPLATE_THEME"] = "blue";
}

$arParams["FILTER_VIEW_MODE"] = (isset($arParams["FILTER_VIEW_MODE"]) && $arParams["FILTER_VIEW_MODE"] == "horizontal") ? "horizontal" : "vertical";
$arParams["POPUP_POSITION"] = (isset($arParams["POPUP_POSITION"]) && in_array($arParams["POPUP_POSITION"], array("left", "right"))) ? $arParams["POPUP_POSITION"] : "left";

//Подсказки
foreach ($arResult["ITEMS"] as $key => $arItem) {
	$res = CIBlockProperty::GetByID($arItem['ID']);
	if ($ar_res = $res->GetNext())
		$arResult["ITEMS"][$key]["HINT"] = $ar_res['HINT'];
}

//Получить ID элементов раздела
$arItems = array();
$arFilter = array(
	"IBLOCK_ID"           => $arParams["IBLOCK_ID"],
	"ACTIVE"              => "Y",
	"SECTION_ID"          => $arResult["SECTION"]["ID"],
	"INCLUDE_SUBSECTIONS" => "Y"
);
$res = CIBlockElement::GetList(array('sort' => 'asc'), $arFilter, false, false, array("ID"));
while ($ob = $res->GetNextElement()) {
	$arFields = $ob->GetFields();
	$arItems[] = $arFields["ID"];
}

//Получить ID разделов раздела
$arSections = array();
$arFilter = array(
	'IBLOCK_ID'      => $arParams["IBLOCK_ID"],
	'<=LEFT_BORDER'  => $arResult['SECTION']['LEFT_MARGIN'],
	'>=RIGHT_BORDER' => $arResult['SECTION']['RIGHT_MARGIN'],
);
$rsSect = CIBlockSection::GetList(array('left_margin' => 'asc'),$arFilter);
while ($arSect = $rsSect->GetNext()) {
   $arSections[] = $arSect['ID'];
}

//Акции
$stock = true;
$arResult["STOCK"] = array();

$arFilter = array(
	"IBLOCK_ID"      => 6,
	"ACTIVE_DATE"    => "Y",
	"ACTIVE"         => "Y",
);

if (!empty($arItems) && !empty($arSections))
	$arFilter[] = array(
		"LOGIC" => "OR",
		array("PROPERTY_goods" => $arItems),
		array("PROPERTY_catalog_section" => $arSections),
	);
elseif (!empty($arItems))
	$arFilter["PROPERTY_goods"] = $arItems;
elseif (!empty($arSections))
	$arFilter["PROPERTY_catalog_section"] = $arSections;
else
	$stock = false;

if ($stock) {
	$res = CIBlockElement::GetList(array('sort' => 'asc'), $arFilter, false, false, array());
	while ($ob = $res->GetNextElement()) {
		$arFields = $ob->GetFields();
		$arResult["STOCK"][$arFields["ID"]] = $arFields["NAME"];
	}
}
