<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$nav = array();
$arSelect = Array("ID", "NAME", 'DETAIL_PAGE_URL');
$arFilter = Array("IBLOCK_ID" => $arParams['IBLOCK_ID'], "ACTIVE" => "Y", "ACTIVE_DATE" => "Y", "CHECK_PERMISSIONS" => "Y");
if(!$USER->IsAuthorized()){
    $arFilter['REGISTERED_ONLY'] = false;
}
$res = CIBlockElement::GetList($arParams['ORDER'], $arFilter, false, Array("nElementID" => $arResult['ID'],
                                                                           'nPageSize' => 1), $arSelect);
while($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields();
    $nav[] = $arFields;
}

if(count($nav) == 2) {
    if($nav[0]['ID'] == $arResult['ID']) {
        $arResult['NEXT'] = $nav[1]['DETAIL_PAGE_URL'];
    }
    elseif($nav[1]['ID'] == $arResult['ID']) {
        $arResult['PREV'] = $nav[0]['DETAIL_PAGE_URL'];
    }
}
elseif(count($nav) == 3) {
    if($nav[0]['ID'] != $arParams['ID']) {
        $arResult['PREV'] = $nav[0]['DETAIL_PAGE_URL'];
    }

    if($nav[count($nav) - 1]['ID'] != $arParams['ID']) {
        $arResult['NEXT'] = $nav[count($nav) - 1]['DETAIL_PAGE_URL'];
    }
}

if(!empty($arResult["PROPERTIES"]["goods"]["VALUE"])) {
    $arSelect = array('ID',
                      'NAME',
                      'DETAIL_PAGE_URL',
                      "CATALOG_GROUP_1",
                      "PROPERTY_SKU",
                      "PROPERTY_PHOTOS",
                      "PREVIEW_PICTURE");
    $arFilter = array(
        "ID" => $arResult["PROPERTIES"]["goods"]["VALUE"],
        'ACTIVE' => "Y"
    );

    $res = CIBlockElement::GetList(
        false,
        $arFilter,
        false,
        false,
        $arSelect);

    while($ob = $res->GetNextElement()) {

        $arFields = $ob->GetFields();
        $arFields['PRICE'] = BXHelper::calculateDicountPrice($arFields, 1, $arParams['PRICE_CODE'][0], SITE_ID, $arFields['CATALOG_CURRENCY_1']);
        $arFields["PROPERTIES"] = $ob->GetProperties();

        if(!isset($arResult["this_goods"][$arFields['ID']])) {
            $arResult["this_goods"][$arFields['ID']] = $arFields;
        }
        else {
            $arResult["this_goods"][$arFields['ID']]['PROPERTY_SKU_VALUE'] = $arFields['PROPERTY_SKU_VALUE'];
            $arResult["this_goods"][$arFields['ID']]['PROPERTY_PHOTOS_VALUE'] = $arFields['PROPERTY_PHOTOS_VALUE'];
        }
    }
    foreach($arResult['this_goods'] as $i => $arCollItem) {
        $sections_for_links[] = $arCollItem['IBLOCK_SECTION_ID'];
    }
}

$sections_for_links = array_unique($sections_for_links);
$arSectionLinksProperties = BXHelper::getPropertySectionLinks(CATALOG_IBLOCK_ID, $sections_for_links, 3, 3, array(), array('SMART_FILTER' => 'Y'));
$arSectionProps = array();

foreach($arSectionLinksProperties as $arSectionProp) {
    $arSectionProps[$arSectionProp['ID']][$arSectionProp['CODE']] = $arSectionProp;
    $prop_ids[] = $arSectionProp['PROPERTY_ID'];
}

$prop_section_sort_result = \CUSTOM\Entity\SectionPropertySortTable::getList(array("select" => array("UF_SECTION_ID",
                                                                                                     "UF_PROPERTY_ID",
                                                                                                     "UF_SORT"),
                                                                                   "filter" => array("UF_PROPERTY_ID" => $prop_ids,
                                                                                                     'UF_SECTION_ID' => $sections_for_links)));

while($next = $prop_section_sort_result->fetch()) {
    $section_prop_sort[] = $next;
}
$brands = [];
$dbBrands = CIBlockElement::GetList([], ['IBLOCK_ID' => BRAND_IBLOCK_ID], false, false);
while($brand = $dbBrands->GetNext()) {
    $brands[$brand['ID']] = $brand['CODE'];
}
$arAdjacentCollsBrandsIds = array();
foreach(array('this_goods') as $i => $result_key) {
    foreach($arResult[$result_key] as &$arResultItem) {
        $arResultItem['DETAIL_PAGE_URL'] = HogartHelpers::rebuildBrandElementHref($arResultItem['DETAIL_PAGE_URL'], $brands[$arResultItem['PROPERTIES']['brand']['VALUE']]);
        foreach($arResultItem['PROPERTIES'] as $code => &$arItemProp) {
            if(!empty($arSectionProps[$arResultItem['IBLOCK_SECTION_ID']][$arItemProp['CODE']])) {
                $arItemProp = array_merge($arItemProp, $arSectionProps[$arResultItem['IBLOCK_SECTION_ID']][$arItemProp['CODE']]);
            }
            if($arItemProp['CODE'] == 'goods' || $arItemProp['CODE'] == 'brand') {
                $arAdjacentCollsBrandsIds[] = $arItemProp['VALUE'];
            }
            foreach($section_prop_sort as $arSort) {
                if($arResultItem['IBLOCK_SECTION_ID'] == $arSort['UF_SECTION_ID'] && $arSort['UF_PROPERTY_ID'] == $arItemProp['ID']) {
                    $arItemProp['CUSTOM_SECTION_SORT'] = $arSort['UF_SORT'];
                }
            }

            $arResultProperty['DISPLAY_EXPANDED_SORT'] = intval($arResultProperty['DISPLAY_EXPANDED'] == "Y") * 100;
        }
        $arResultItem['PROPERTIES'] = BXHelper::complex_sort($arResultItem['PROPERTIES'], array('DISPLAY_EXPANDED_SORT' => 'DESC',
                                                                                                'CUSTOM_SECTION_SORT' => 'ASC'), false);

        HogartHelpers::mergeRangePropertiesForItem($arResultItem['PROPERTIES']);
        foreach($arResultItem['PROPERTIES'] as $arProp) {
            if(!empty($arProp['VALUE'])) {
                if($arProp['DISPLAY_EXPANDED'] == "Y") {
                    $arResultItem['SHOW_PROPS'][] = $arProp;
                }
                else {
                    $arResultItem['HIDDEN_PROPS'][] = $arProp;
                }
            }
        }
        if(empty($arResultItem['SHOW_PROPS'])) {
            $arResultItem['SHOW_PROPS'] = $arResultItem['HIDDEN_PROPS'];
        }
    }
}
$linkedElements = BXHelper::getElements(array(), array('IBLOCK_ID' => array(COLLECTION_IBLOCK_ID, BRAND_IBLOCK_ID),
                                                       'ID' => $arAdjacentCollsBrandsIds), false, false, array("ID",
                                                                                                               "CODE",
                                                                                                               "NAME"), true, 'ID');
foreach(array('this_goods') as $i => $result_key) {
    foreach($arResult[$result_key] as &$arResultItem) {
        $arResultItem['COLLECTION_NAME'] = $linkedElements['RESULT'][$arResultItem['PROPERTIES']['goods']['VALUE']]['NAME'];
        $arResultItem['BRAND_NAME'] = $linkedElements['RESULT'][$arResultItem['PROPERTIES']['brand']['VALUE']]['NAME'];
        unset($arResultItem['PROPERTIES']);
    }
}