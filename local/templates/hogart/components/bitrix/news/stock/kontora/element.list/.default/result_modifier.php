<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arFilter = array(
	"IBLOCK_ID" => $arParams["IBLOCK_ID"], 
	"ACTIVE"    => "Y"
);

//Получить бренды
$res = CIBlockElement::GetList(array(), $arFilter, array("PROPERTY_BRAND"), false, array());
while ($ob = $res->GetNextElement()) {
	$arFields = $ob->GetFields();
	if (!empty($arFields["PROPERTY_BRAND_VALUE"])) {
		$resBrand = CIBlockElement::GetByID($arFields["PROPERTY_BRAND_VALUE"]);
		if ($arBrand = $resBrand->GetNext())
			$arResult["FILTER"]["BRANDS"][] = array(
				"ID"    => $arFields["PROPERTY_BRAND_VALUE"],
				"VALUE" => $arBrand["NAME"]
			);
	}
}
usort($arResult["FILTER"]["BRANDS"], function($a, $b){
    return strnatcmp($a['VALUE'], $b['VALUE']);
});
//Получить направления
$arFilterDirection = array(
	"IBLOCK_ID"   => 1,
	"DEPTH_LEVEL" => 1
);
$rsSect = CIBlockSection::GetList(array("sort" => "asc"), $arFilterDirection);
while ($arSect = $rsSect->GetNext()) {
   $arResult["FILTER"]["DIRECTIONS"][] = $arSect;
}

//Получить типы
$res = CIBlockElement::GetList(array("property_catalog_section.name" => "asc"), $arFilter, array("PROPERTY_catalog_section"), false, array());
while($ob = $res->GetNextElement())
{
	$arFields = $ob->GetFields();
	if (!empty($arFields["PROPERTY_CATALOG_SECTION_VALUE"])) {
		$resSection = CIBlockSection::GetByID($arFields["PROPERTY_CATALOG_SECTION_VALUE"]);
		if ($ar_res = $resSection->GetNext())
			$arResult["FILTER"]["TYPES"][] = array(
				"ID"    => $arFields["PROPERTY_CATALOG_SECTION_VALUE"],
				"VALUE" => $ar_res["NAME"]
			);
	}
}

usort($arResult["FILTER"]["TYPES"], function($a, $b){
    return strnatcmp($a['VALUE'], $b['VALUE']);
});

//Получить города
$res = CIBlockElement::GetList(array(), $arFilter, array("PROPERTY_city"), false, array());
while ($ob = $res->GetNextElement()) {
	$arFields = $ob->GetFields();
	$resCity = CIBlockElement::GetByID($arFields["PROPERTY_CITY_VALUE"]);
	if ($arCity = $resCity->GetNext())
		$arResult["FILTER"]["CITY"][] = array(
			"ID"    => $arFields["PROPERTY_CITY_VALUE"],
			"VALUE" => $arCity["NAME"]
		);
}


$arResult["custom_filter_count"]["sale"] = CIBlockElement::GetList(array(), array($arFilter,array("!PROPERTY_sale"=>false)), array());
$arResult["custom_filter_count"]["markdown"] = CIBlockElement::GetList(array(), array($arFilter,array("!PROPERTY_markdown"=>false)), array());
?>