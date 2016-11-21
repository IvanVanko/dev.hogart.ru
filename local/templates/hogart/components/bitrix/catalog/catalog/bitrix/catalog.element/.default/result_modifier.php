<?
//use Bitrix\Main\Type\Collection;
//use Bitrix\Currency\CurrencyTable;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

$photos = $arResult['DISPLAY_PROPERTIES']['photos']['VALUE'];
rsort($photos);
$arResult['DISPLAY_PROPERTIES']['photos']['VALUE'] = $photos;

//Documents
$access_level = ($USER->IsAuthorized()) ? array(1, 2) : 1;
$ar_res = CIBlockElement::GetList(array("sort" => "asc"),
    array("ID" => $arResult["PROPERTIES"]["docs"]["VALUE"],
          "ACTIVE" => "Y",
          "PROPERTY_access_level" => $access_level), false, false, array());
while ($ob = $ar_res->GetNextElement()) {
	$arFields = $ob->GetFields();
	$arFields["PROPERTIES"] = $ob->GetProperties();
	$arFields["FILE"] = CFile::GetFileArray($arFields["PROPERTIES"]["file"]["VALUE"]);
	$arFields["FILE"]["FILE_SIZE"] = round($arFields["FILE"]["FILE_SIZE"] / 1048576, 2);
	$info = new SplFileInfo($arFields["FILE"]["FILE_NAME"]);
	$arFields["FILE"]["EXTENTION"] = $info->getExtension();
	$arResult["DOCS"][] = $arFields;
}

if (!empty($arResult['IBLOCK_SECTION_ID']))
    $sections_for_links = array($arResult['IBLOCK_SECTION_ID']);

$brands = BXHelper::getElementLinkEnum($arResult['DISPLAY_PROPERTIES']['brand']['ID'], false, array(), 'CODE');
$arResult['PRODUCT_PROPERTIES'] = $result_properties;

global $USER;
$account = \Hogart\Lk\Entity\AccountTable::getAccountByUserID($USER->GetID());
$storeFilter = [
];

if ($account['id']) {
    $accountStores = array_reduce(\Hogart\Lk\Entity\AccountStoreRelationTable::getByAccountId($account['id']), function ($result, $store) {
        $result[] = $store['ID'];
        return $result;
    }, []);

    if (!empty($accountStores)) {
        $storeFilter['ID'] = $accountStores;
    }

    $prices = \Hogart\Lk\Entity\CompanyDiscountTable::prepareFrontByAccount($account['id'], [
        $arResult['ID'] => $arResult["PRICES"]["BASE"]["VALUE"]
    ]);

    $arResult["PRICES"]["BASE"]["VALUE"] = $prices[$arResult['ID']]['price'];
    $arResult["PRICES"]["BASE"]["DISCOUNT_VALUE"] = $prices[$arResult['ID']]['price'];
    $arResult["PRICES"]["BASE"]["DISCOUNT_DIFF"] = $prices[$arResult['ID']]['discount_amount'];
    $arResult["PRICES"]["BASE"]["DISCOUNT_DIFF_PERCENT"] = (float)$prices[$arResult['ID']]['discount'];
}

$stores = BXHelper::getStores(array(), $storeFilter, false, false, array('ID', 'TITLE', 'ADDRESS'), 'ID');
$arResult["STORES"] = $stores;

$prepareRelatedItems = function (&$result = [], $base_link = null, $arFilter = [], $arSelect = []) use ($arParams, $arResult, $account, $storeFilter) {

    $arSelect = array_merge(
        ['ID', 'NAME', 'DETAIL_PAGE_URL', 'CATALOG_MEASURE', "CATALOG_GROUP_1", "PROPERTY_SKU", "PROPERTY_PHOTOS", "PREVIEW_PICTURE"],
        $arSelect
    );

    $arFilter = array_merge(
        [
            "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
            "IBLOCK_ID" => $arParams['IBLOCK_ID'],
            'ACTIVE' => "Y",
            '!ID' => $arResult['ID']
        ],
        $arFilter
    );

    $arNavParams = array(
        "nPageSize" => 4
    );

    $res = CIBlockElement::GetList(
        [],
        $arFilter,
        ["ID"],
        $arNavParams,
        []);

    while($ob = $res->Fetch()) {
        $ob = CIBlockElement::GetList([], array_merge($arFilter, ['ID' => $ob['ID']]), false, false, $arSelect)->GetNextElement();
        $arFields = $ob->GetFields();
        $arFields["PROPERTIES"] = $ob->GetProperties();
        if (!isset($result['ITEMS'][$arFields['ID']])) {
            $result['ITEMS'][$arFields['ID']] = $arFields;
        } else {
            $result['ITEMS'][$arFields['ID']]['PROPERTY_SKU_VALUE'] = $arFields['PROPERTY_SKU_VALUE'];
            $result['ITEMS'][$arFields['ID']]['PROPERTY_PHOTOS_VALUE'] = $arFields['PROPERTY_PHOTOS_VALUE'];
        }

        if (0 < $arFields['CATALOG_MEASURE'])
        {
            $rsMeasures = CCatalogMeasure::getList(
                array(),
                array('ID' => $arFields['CATALOG_MEASURE']),
                false,
                false,
                array('ID', 'SYMBOL_RUS')
            );
            if ($arMeasure = $rsMeasures->GetNext())
            {
                $result['ITEMS'][$arFields['ID']]['CATALOG_MEASURE_NAME'] = $arMeasure['SYMBOL_RUS'];
                $result['ITEMS'][$arFields['ID']]['~CATALOG_MEASURE_NAME'] = $arMeasure['~SYMBOL_RUS'];
            }
        }

        if ('' == $result['ITEMS'][$arFields['ID']]['CATALOG_MEASURE_NAME']) {
            $arDefaultMeasure = CCatalogMeasure::getDefaultMeasure(true, true);
            $result['ITEMS'][$arFields['ID']]['CATALOG_MEASURE_NAME'] = $arDefaultMeasure['SYMBOL_RUS'];
            $result['ITEMS'][$arFields['ID']]['~CATALOG_MEASURE_NAME'] = $arDefaultMeasure['~SYMBOL_RUS'];
        }
    }


    $prices = array_reduce($result['ITEMS'], function ($result, $item) {
        $result[$item["ID"]] = $item["CATALOG_PRICE_1"];
        return $result;
    }, []);

    $prices = \Hogart\Lk\Entity\CompanyDiscountTable::prepareFrontByAccount($account['id'], $prices);
    $storeAmounts = \Hogart\Lk\Entity\StoreAmountTable::getStoreAmountByItemsId(array_keys($result['ITEMS']), $storeFilter['ID']);

    foreach ($result['ITEMS'] as $id => &$arCollItem) {
        $arCollItem["PRICES"]["BASE"]["CURRENCY"] = $arCollItem["CATALOG_CURRENCY_1"];
        $arCollItem["PRICES"]["BASE"]["VALUE"] = $arCollItem["CATALOG_PRICE_1"];
        $arCollItem["PRICES"]["BASE"]["DISCOUNT_VALUE"] = $prices[$id]['price'];
        $arCollItem["PRICES"]["BASE"]["DISCOUNT_DIFF"] = $prices[$id]['discount_amount'];
        $arCollItem["PRICES"]["BASE"]["DISCOUNT_DIFF_PERCENT"] = (float)$prices[$id]['discount'];

        if (!empty($arCollItem['IBLOCK_SECTION_ID'])) {
            $sections_for_links[] = $arCollItem['IBLOCK_SECTION_ID'];
        }
        $arCollItem["STORE_AMOUNTS"] = !empty($storeAmounts[$id]) ? $storeAmounts[$id] : [];
        $arCollItem['CATALOG_QUANTITY'] = 0;
        foreach ($arCollItem["STORE_AMOUNTS"] as $amount) {
            $arCollItem['CATALOG_QUANTITY'] += $amount['stock'];
        }
    }

    if (null !== $base_link) {
        $navComponentParameters["BASE_LINK"] = $base_link;


        $result["NAV_STRING"] = $res->GetPageNavStringEx(
            $navComponentObject,
            $arParams["PAGER_TITLE"],
            $arParams["PAGER_TEMPLATE"],
            $arParams["PAGER_SHOW_ALWAYS"],
            $this,
            $navComponentParameters
        );

        $strNavQueryString = ($navComponentObject->arResult["NavQueryString"] != "" ? $navComponentObject->arResult["NavQueryString"]."&amp;" : "");

        if ($navComponentObject->arResult["NavPageNomer"] - 1 > 0) {
            $result["PREV_LINK"] = $navComponentObject->arResult["sUrlPath"] . "?" . $strNavQueryString . "PAGEN_" . $navComponentObject->arResult["NavNum"] . "=" . ($navComponentObject->arResult["NavPageNomer"]-1);
        }

        if ($navComponentObject->arResult["NavPageNomer"] + 1 <= $navComponentObject->arResult["NavPageCount"]) {
            $result["NEXT_LINK"] = $navComponentObject->arResult["sUrlPath"] . "?" . $strNavQueryString . "PAGEN_" . $navComponentObject->arResult["NavNum"] . "=" . ($navComponentObject->arResult["NavPageNomer"]+1);
        }
    }

    return $result['ITEMS'];
};

//buy_with_this
if (!empty($arResult["PROPERTIES"]["buy_with_this"]["VALUE"])) {
    $arFilter = [
        "ID" => $arResult["PROPERTIES"]["buy_with_this"]["VALUE"],
    ];
    $base_link = CHTTP::urlAddParams(GetPagePath(false, false), ["buy_with_this" => md5(serialize($arResult["PROPERTIES"]["buy_with_this"]["VALUE"]))], array("encode"=>true));
    $prepareRelatedItems($arResult["buy_with_this"], $base_link, $arFilter);
}
foreach ($arResult['buy_with_this']["ITEMS"] as $i => $arCollItem) {
    if (empty($arCollItem['IBLOCK_SECTION_ID'])) continue;
    $sections_for_links[] = $arCollItem['IBLOCK_SECTION_ID'];
}

// related
if (!empty($arResult["PROPERTIES"]["related"]["VALUE"])) {
    $arFilter = [
        "ID" => $arResult["PROPERTIES"]["related"]["VALUE"],
    ];
    $base_link = CHTTP::urlAddParams(GetPagePath(false, false), ["related" => md5(serialize($arResult["PROPERTIES"]["related"]["VALUE"]))], array("encode"=>true));
    $prepareRelatedItems($arResult["related"], $base_link, $arFilter);
}
foreach ($arResult['related']["ITEMS"] as $i => $arCollItem) {
    if (empty($arCollItem['IBLOCK_SECTION_ID'])) continue;
    $sections_for_links[] = $arCollItem['IBLOCK_SECTION_ID'];
}

//alternative
if (!empty($arResult["PROPERTIES"]["alternative"]["VALUE"])) {
    $arFilter = [
        "ID" => $arResult["PROPERTIES"]["alternative"]["VALUE"],
    ];
    $base_link = CHTTP::urlAddParams(GetPagePath(false, false), ["alternative" => md5(serialize($arResult["PROPERTIES"]["alternative"]["VALUE"]))], array("encode"=>true));
    $prepareRelatedItems($arResult["alternative"], $base_link, $arFilter);
}
foreach ($arResult['alternative']["ITEMS"] as $i => $arCollItem) {
    if (empty($arCollItem['IBLOCK_SECTION_ID'])) continue;
    $sections_for_links[] = $arCollItem['IBLOCK_SECTION_ID'];
}


if (!empty($arResult["PROPERTIES"]["collection"]["VALUE"])) {
    $arFilter = array(
        "PROPERTY_collection" => $arResult["PROPERTIES"]["collection"]["VALUE"],
    );

    $base_link = CHTTP::urlAddParams(GetPagePath(false, false), ["collection" => $arResult["PROPERTIES"]["collection"]["VALUE"]], array("encode"=>true));
    $prepareRelatedItems($arResult["this_collection"], $base_link, $arFilter);

    $collection = CIBlockElement::GetByID($arResult["PROPERTIES"]["collection"]["VALUE"])->GetNext();
    $arResult["DISPLAY_PROPERTIES"]["collection"]["PREVIEW_TEXT"] = $collection["PREVIEW_TEXT"];
    $arResult["DISPLAY_PROPERTIES"]["collection"]["DETAIL_TEXT"] = $collection["DETAIL_TEXT"];
}

$sections_for_links = array_unique($sections_for_links);
$arSectionLinksProperties = BXHelper::getPropertySectionLinks($arParams['IBLOCK_ID'], $sections_for_links, 3, 3, array(), array('SMART_FILTER' => 'Y'));
$arSectionProps = array();
foreach ($arSectionLinksProperties as $arSectionProp) {
    $arSectionProps[$arSectionProp['ID']][$arSectionProp['CODE']] = $arSectionProp;
    $prop_ids[] = $arSectionProp['PROPERTY_ID'];
}

if (!empty($prop_ids)) {
    $prop_section_sort_result = \CUSTOM\Entity\SectionPropertySortTable::getList(array("select" => array("UF_SECTION_ID", "UF_PROPERTY_ID","UF_SORT"), "filter" => array("UF_PROPERTY_ID" => $prop_ids, 'UF_SECTION_ID' => $sections_for_links)));
    while ($next = $prop_section_sort_result->fetch()) {
        $section_prop_sort[] = $next;
    }
}

$arAdjacentCollsBrandsIds = array();
foreach (array('this_collection','alternative','related','buy_with_this') as $i => $result_key) {
    foreach ($arResult[$result_key]['ITEMS'] as &$arResultItem) {
        $arResultItem['DETAIL_PAGE_URL'] = HogartHelpers::rebuildBrandElementHref($arResultItem['DETAIL_PAGE_URL'], $brands[$arResultItem['PROPERTIES']['brand']['VALUE']]['VALUE']);
        foreach ($arResultItem['PROPERTIES'] as $code =>  &$arItemProp) {
            if (!empty($arSectionProps[$arResultItem['IBLOCK_SECTION_ID']][$arItemProp['CODE']])) {
                $arItemProp = array_merge($arItemProp, $arSectionProps[$arResultItem['IBLOCK_SECTION_ID']][$arItemProp['CODE']]);
            }
            if ($arItemProp['CODE'] == 'collection' || $arItemProp['CODE'] == 'brand') {
                $arAdjacentCollsBrandsIds[] = $arItemProp['VALUE'];
            }
            foreach ($section_prop_sort as $arSort) {
                if ($arResultItem['IBLOCK_SECTION_ID'] == $arSort['UF_SECTION_ID'] && $arSort['UF_PROPERTY_ID'] == $arItemProp['ID']) {
                    $arItemProp['CUSTOM_SECTION_SORT'] = $arSort['UF_SORT'];
                }
            }
            $arResultProperty['DISPLAY_EXPANDED_SORT'] = intval($arResultProperty['DISPLAY_EXPANDED'] == "Y")*100;
        }
        $arResultItem['PROPERTIES'] = BXHelper::complex_sort($arResultItem['PROPERTIES'], array('DISPLAY_EXPANDED_SORT' => 'DESC', 'CUSTOM_SECTION_SORT' => 'ASC'), false);
        HogartHelpers::mergeRangePropertiesForItem($arResultItem['PROPERTIES']);
        foreach ($arResultItem['PROPERTIES'] as $arProp) {
            if (!empty($arProp['VALUE'])) {
                if ($arProp['DISPLAY_EXPANDED'] == "Y") {
                    $arResultItem['SHOW_PROPS'][] = $arProp;
                } else {
                    $arResultItem['HIDDEN_PROPS'][] = $arProp;
                }
            }
        }
        if (empty($arResultItem['SHOW_PROPS'])) {
            $arResultItem['SHOW_PROPS'] = $arResultItem['HIDDEN_PROPS'];
        }
    }
}
$linkedElements = BXHelper::getElements(array(), array('IBLOCK_ID' => array(COLLECTION_IBLOCK_ID, BRAND_IBLOCK_ID), 'ID' => $arAdjacentCollsBrandsIds), false, false, array("ID", "CODE", "NAME"), true, 'ID');
foreach (array('this_collection','alternative','related','buy_with_this') as $i => $result_key) {
    foreach ($arResult[$result_key]["ITEMS"] as &$arResultItem) {
        $arResultItem['COLLECTION_NAME'] = $linkedElements['RESULT'][$arResultItem['PROPERTIES']['collection']['VALUE']]['NAME'];
        $arResultItem['BRAND_NAME'] = $linkedElements['RESULT'][$arResultItem['PROPERTIES']['brand']['VALUE']]['NAME'];
        unset($arResultItem['PROPERTIES']);
    }
}


HogartHelpers::mergeRangePropertiesForItem($arResult['PROPERTIES']);
foreach ($arResult['PROPERTIES'] as $key => &$arResultProperty) {
    if (isset($arSectionProps[$arResult['IBLOCK_SECTION_ID']][$arResultProperty['CODE']])) {
        $arResultProperty = array_merge($arSectionProps[$arResult['IBLOCK_SECTION_ID']][$arResultProperty['CODE']], $arResultProperty);
        foreach ($section_prop_sort as $arSort) {
            if ($arResult['IBLOCK_SECTION_ID'] == $arSort['UF_SECTION_ID'] && $arSort['UF_PROPERTY_ID'] == $arResultProperty['ID']) {
                $arResultProperty['CUSTOM_SECTION_SORT'] = $arSort['UF_SORT'];
            }
        }
        $arResultProperty['DISPLAY_EXPANDED_SORT'] = intval($arResultProperty['DISPLAY_EXPANDED'] == "Y")*100;
    } else {
        unset($arResult['PROPERTIES'][$key]);
    }
}

$arResult['PROPERTIES'] = BXHelper::complex_sort($arResult['PROPERTIES'], array('DISPLAY_EXPANDED_SORT' => 'DESC', 'CUSTOM_SECTION_SORT' => 'ASC'), false);

foreach ($arResult['PROPERTIES'] as $arProp) {
    if (!empty($arProp['VALUE'])) {
        $arPropertyDump[] = array($arProp['ID'], $arProp['NAME'], $arProp['CUSTOM_SECTION_SORT'], $arProp['VALUE'], $arProp['DISPLAY_EXPANDED']);
    }
}

$res = CIBlockElement::GetByID($arResult['DISPLAY_PROPERTIES']['brand']['VALUE']);
if($ar_res = $res->GetNext())
$arResult["CUSTOM"]["BRAND_NAME"]=$ar_res['NAME'];


if ($arParams['STORES_FILTERED'] != 'Y') {
    $catalog_store_filter = array('LOGIC' => 'OR');
    foreach ($stores as $store_id) {
        $store_keys[] = 'CATALOG_STORE_AMOUNT_'.$store_id['ID'];
        $catalog_store_filter[] = array('>CATALOG_STORE_AMOUNT_'.$store_id['ID'] => 0);
    }
    $custom_filter[] = $catalog_store_filter;
    $custom_filter['ID'] = $arResult["ID"];
    $elements = BXHelper::getElements(array(), $custom_filter, false, false, array('ID'), true, 'ID');

    if (isset($elements['RESULT'][$arResult["ID"]])) {
        foreach ($store_keys as $s_key) {
            $arResult[$s_key] +=intval($elements['RESULT'][$arResult["ID"]][$s_key]);
        }
    }

    $account = \Hogart\Lk\Entity\AccountTable::getAccountByUserID($USER->GetID());
    $storeFilter = [
    ];

    if ($account['id']) {
        $accountStores = array_reduce(\Hogart\Lk\Entity\AccountStoreRelationTable::getByAccountId($account['id']), function ($result, $store) {
            $result[] = $store['ID'];
            return $result;
        }, []);

        if (!empty($accountStores)) {
            $storeFilter['ID'] = $accountStores;
        }

        $storeAmounts = \Hogart\Lk\Entity\StoreAmountTable::getStoreAmountByItemsId([$arResult['ID']], $storeFilter['ID']);
        $arResult["STORE_AMOUNTS"] = !empty($storeAmounts[$arResult['ID']]) ? $storeAmounts[$arResult['ID']] : [];
        $arResult['CATALOG_QUANTITY'] = 0;
        foreach ($arResult["STORE_AMOUNTS"] as $amount) {
            $arResult['CATALOG_QUANTITY'] += $amount['stock'];
        }
    }
}
