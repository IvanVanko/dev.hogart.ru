<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/*$arFilter = array(
	"IBLOCK_ID" => $arParams["IBLOCK_ID"], 
	"ACTIVE"    => "Y"
);*/
$arFilter = array(
    "IBLOCK_ID"     => $arParams["IBLOCK_ID"],
    "ACTIVE"        => "Y",
//    'PROPERTY_sem_end_date' => false,
);

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

//Получить направления
$directionsId = array();
$res = CIBlockElement::GetList(array(), $arFilter, array("PROPERTY_direction"), false, array());
while ($ob = $res->GetNextElement()) {
	$arFields = $ob->GetFields();
	$directionsId[] = $arFields['PROPERTY_DIRECTION_VALUE'];
}

$directions = array();
$arFilterDirection = array(
	'ID' => $directionsId,
);
$rsSect = CIBlockSection::GetList(array("sort" => "asc"), $arFilterDirection);
while ($arSect = $rsSect->GetNext()) {
   $directions[] = $arSect;
}

$arResult["FILTER"]["DIRECTIONS"] = array();
foreach ($directions as $arSection) {
	$arFilter = array(
		"IBLOCK_ID"      => $arSection['IBLOCK_ID'],
		'<=LEFT_BORDER'  => $arSection['LEFT_MARGIN'], 
		'>=RIGHT_BORDER' => $arSection['RIGHT_MARGIN'],
	);
	$rsSect = CIBlockSection::GetList(array("left_margin" => "asc"), $arFilter);
	$firstSectionId = 0;
	while ($arSect = $rsSect->GetNext()) {
	   	$section = array(
	   		'ID'           => $arSect['ID'],
	   		'NAME'         => $arSect['NAME'],
	   		'LEVEL'        => $arSect['DEPTH_LEVEL'],
	   		'LEFT_MARGIN'  => $arSect['LEFT_MARGIN'],
	   		'RIGHT_MARGIN' => $arSect['RIGHT_MARGIN'],
	   	);

	   	if ($arSect['DEPTH_LEVEL'] == 1) {
	   		$arResult["FILTER"]["DIRECTIONS"][$arSect['ID']] = $section;
	   		$firstSectionId = $arSect['ID'];
	   	}
		elseif ($arSect['DEPTH_LEVEL'] == 2) {
			$arResult["FILTER"]["DIRECTIONS"][$arSect['IBLOCK_SECTION_ID']]['SECTIONS'][$arSect['ID']] = $section;
		}
		else {
			//$arResult["FILTER"]["DIRECTIONS"][$firstSectionId]['SECTIONS'][$arSect['IBLOCK_SECTION_ID']]['SECTIONS'][] = $section;
			$arResult["FILTER"]["DIRECTIONS"][$firstSectionId]['SECTIONS'][$arSect['ID']] = $section;
		}
	}
}