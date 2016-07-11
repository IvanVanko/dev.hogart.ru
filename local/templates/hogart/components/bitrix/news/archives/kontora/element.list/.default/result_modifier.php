<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arFilter = array(
    "IBLOCK_ID"     => $arParams["IBLOCK_ID"],
    "ACTIVE"        => "Y",
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
				"VALUE" => $arBrand["NAME"],
				"CHECKED" => in_array($arBrand['ID'], $_REQUEST['brand'])
			);
	}
}
usort($arResult['FILTER']['BRANDS'], function ($a, $b) { return ($a["VALUE"] > $b["VALUE"]) & ($a["CHECKED"] <= $b["CHECKED"]); });
