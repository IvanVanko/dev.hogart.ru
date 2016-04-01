<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arFilter = array(
	"IBLOCK_ID" => $arParams["IBLOCK_ID"], 
	"ACTIVE"    => "Y"
);

//Получить все Теги
$arResult['FILTER']['TAG'] = array();
$res = CIBlockElement::GetList(array("PROPERTYSORT_TAG" => "ASC"), $arFilter, array("PROPERTY_TAG_VALUE", "PROPERTY_TAG_ENUM_ID"), false, array());
while ($ob = $res->GetNextElement()) {
	$arFields = $ob->GetFields();
	$arResult['FILTER']['TAG'][] = $arFields;
}

//Получить бренды
$arResult["FILTER"]["BRANDS"] = array();
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
$arDirectionsID = array();
$res = CIBlockElement::GetList(array(), $arFilter, array("PROPERTY_catalog_section"), false, array());
while ($ob = $res->GetNextElement()) {
	$arFields = $ob->GetFields();
	$arDirectionsID[] = $arFields['PROPERTY_CATALOG_SECTION_VALUE'];
}

$arDirections = array();
$rsSect = CIBlockSection::GetList(array("left_margin" => "asc"), array('ID' => $arDirectionsID));
while ($arSect = $rsSect->GetNext()) {
   	$arDirections[] = $arSect;
}

$arResult["FILTER"]["DIRECTIONS"] = array();
foreach ($arDirections as $arSection) {
	$arFilterDirection = array(
		"IBLOCK_ID"      => 1,
		"DEPTH_LEVEL"    => 1,
		'<=LEFT_BORDER'  => $arSection['LEFT_MARGIN'], 
		'>=RIGHT_BORDER' => $arSection['RIGHT_MARGIN'],
	);
	$rsSect = CIBlockSection::GetList(array("left_margin" => "asc"), $arFilterDirection);
	while ($arSect = $rsSect->GetNext()) {
		if (!array_key_exists($arSect['ID'], $arResult['FILTER']['DIRECTIONS']))
	   		$arResult["FILTER"]["DIRECTIONS"][$arSect['ID']] = $arSect;
	}
}
usort($arResult["FILTER"]["DIRECTIONS"], function($a, $b){
	return strnatcmp($a['NAME'], $b['NAME']);
});
//Получить типы
$arResult["FILTER"]["TYPES"] = array();

$types = array();
$res = CIBlockElement::GetList(array("property_catalog_section.name" => "asc"), $arFilter, array("PROPERTY_catalog_section"), false, array());
while($ob = $res->GetNextElement())
{
	$arFields = $ob->GetFields();
	if (!empty($arFields["PROPERTY_CATALOG_SECTION_VALUE"])) {
		$typesID[] = $arFields["PROPERTY_CATALOG_SECTION_VALUE"];
		$resSection = CIBlockSection::GetByID($arFields["PROPERTY_CATALOG_SECTION_VALUE"]);
		if ($ar_res = $resSection->GetNext())
			$types[] = array(
				"ID"    => $arFields["PROPERTY_CATALOG_SECTION_VALUE"],
				"VALUE" => $ar_res["NAME"]
			);
	}
}
usort($types, function($a, $b){
	return strnatcmp($a['VALUE'], $b['VALUE']);
});
if (isset($_REQUEST['direction'])) {
	$arFilterSection = array(
		"IBLOCK_ID"      => 1,
		'>LEFT_BORDER'  => $_REQUEST['direction_'.$_REQUEST['direction'].'_left'],
        '<RIGHT_BORDER' => $_REQUEST['direction_'.$_REQUEST['direction'].'_right'],
	);
	$rsSect = CIBlockSection::GetList(array("left_margin" => "asc"), $arFilterSection);
	while ($arSect = $rsSect->GetNext()) {
		if (in_array($arSect["ID"], $typesID))
			$arResult["FILTER"]["TYPES"][] = array(
				"ID"    => $arSect["ID"],
				"VALUE" => $arSect["NAME"]
			);
	}
} else {
	$arResult["FILTER"]["TYPES"] = $types;
}

?>