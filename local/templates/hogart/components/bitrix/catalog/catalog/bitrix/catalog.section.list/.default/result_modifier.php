<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$brandProperty = CIBlockProperty::GetList([], ["CODE" => "brand", "IBLOCK_ID" => CATALOG_IBLOCK_ID])->Fetch();
$arParams["FILTER"]["brand"] = $brandProperty["ID"];

$brandRes = CIBlockElement::GetList([], ["IBLOCK_ID" => BRAND_IBLOCK_ID]);
$brands = [];
while (($brandElement = $brandRes->Fetch())) {
    $brands[$brandElement["ID"]] = $brandElement;
}

foreach ($arResult['SECTIONS'] as &$arSection) {
    if ($arSection["DEPTH_LEVEL"] == 3) {
        $sectionBrandRes = CIBlockElement::GetList(["PROPERTY_brand.NAME" => "ASC"], ["IBLOCK_ID" => $arResult["SECTION"]["IBLOCK_ID"], "SECTION_ID" => $arSection["ID"]], ["PROPERTY_brand"], false, ["PROPERTY_BRAND_VALUE"]);
        while ($sectionBrand = $sectionBrandRes->Fetch()) {
            $arSection["BRANDS"][] = $brands[$sectionBrand["PROPERTY_BRAND_VALUE"]];
        }
    }
}
BXHelper::addCachedKeys($this->__component, array('BRANDS'), $arResult);