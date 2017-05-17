<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$brandProperty = CIBlockProperty::GetList([], ["CODE" => "brand", "IBLOCK_ID" => CATALOG_IBLOCK_ID])->Fetch();
$arParams["FILTER"]["brand"] = $brandProperty["ID"];

$brandRes = CIBlockElement::GetList([], ["IBLOCK_ID" => BRAND_IBLOCK_ID], false, false, ["NAME", "ID", "CODE"]);
$brands = [];
$arResult['BRANDS'] = [];
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
        $arSection["BRANDS"] = $sectionsBrand[$arSection["ID"]] ? : [];
        if (!empty($arSection["BRANDS"])) {
            foreach ($arSection["BRANDS"] as $BRAND) {
                if (!isset($arResult['BRANDS'][$BRAND['ID']]) && !empty($BRAND['CODE'])) {
                    $arResult['BRANDS'][$BRAND['ID']] = $BRAND;
                }
                $arResult['BRANDS'][$BRAND['ID']]['SECTIONS'][] = &$arSection;
            }
        }
    }
}

$arResult['BRANDS'] = array_map(function ($BRAND) {
    array_unique($BRAND['SECTIONS']);
    return $BRAND;
}, $arResult['BRANDS']);

usort($arResult['BRANDS'], function ($a, $b) {
    return $a['NAME'] > $b['NAME'];
});

BXHelper::addCachedKeys($this->__component, array('BRANDS'), $arResult);