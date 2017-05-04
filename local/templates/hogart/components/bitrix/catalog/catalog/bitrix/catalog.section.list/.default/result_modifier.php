<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$brandProperty = CIBlockProperty::GetList([], ["CODE" => "brand", "IBLOCK_ID" => CATALOG_IBLOCK_ID])->Fetch();
$arParams["FILTER"]["brand"] = $brandProperty["ID"];

$brandRes = CIBlockElement::GetList([], ["IBLOCK_ID" => BRAND_IBLOCK_ID], false, false, ["NAME", "ID"]);
$brands = [];
while (($brandElement = $brandRes->Fetch())) {
    $brands[$brandElement["ID"]] = $brandElement;
}

$sectionIds = array_unique(array_column($arResult['SECTIONS'], "ID"));
$sectionBrandRes = CIBlockElement::GetList(["PROPERTY_brand.NAME" => "ASC"], ["IBLOCK_ID" => CATALOG_IBLOCK_ID, "SECTION_ID" => $sectionIds], ["IBLOCK_SECTION_ID", "PROPERTY_brand"], false, ["PROPERTY_BRAND_VALUE", "SECTION_ID"]);
$sectionsBrand = [];
while (($s = $sectionBrandRes->GetNext())) {
    $sectionsBrand[$s['IBLOCK_SECTION_ID']][] = $brands[$s['PROPERTY_BRAND_VALUE']];
}

foreach ($arResult['SECTIONS'] as &$arSection) {
    if ($arSection["DEPTH_LEVEL"] == 3) {
        $arSection["BRANDS"] = $sectionsBrand[$arSection["ID"]];
    }
}

BXHelper::addCachedKeys($this->__component, array('BRANDS'), $arResult);