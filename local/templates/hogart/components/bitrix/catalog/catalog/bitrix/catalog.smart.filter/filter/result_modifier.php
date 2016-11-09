<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

if(isset($arParams["TEMPLATE_THEME"]) && !empty($arParams["TEMPLATE_THEME"])) {
    $arAvailableThemes = array();
    $dir = trim(preg_replace("'[\\\\/]+'", "/", dirname(__FILE__)."/themes/"));
    if(is_dir($dir) && $directory = opendir($dir)) {
        while(($file = readdir($directory)) !== false) {
            if($file != "." && $file != ".." && is_dir($dir.$file)) {
                $arAvailableThemes[] = $file;
            }
        }
        closedir($directory);
    }

    if($arParams["TEMPLATE_THEME"] == "site") {
        $solution = COption::GetOptionString("main", "wizard_solution", "", SITE_ID);
        if($solution == "eshop") {
            $theme = COption::GetOptionString("main", "wizard_eshop_adapt_theme_id", "blue", SITE_ID);
            $arParams["TEMPLATE_THEME"] = (in_array($theme, $arAvailableThemes)) ? $theme : "blue";
        }
    }
    else {
        $arParams["TEMPLATE_THEME"] = (in_array($arParams["TEMPLATE_THEME"], $arAvailableThemes)) ? $arParams["TEMPLATE_THEME"] : "blue";
    }
}
else {
    $arParams["TEMPLATE_THEME"] = "blue";
}

$arParams["FILTER_VIEW_MODE"] = (isset($arParams["FILTER_VIEW_MODE"]) && $arParams["FILTER_VIEW_MODE"] == "horizontal") ? "horizontal" : "vertical";
$arParams["POPUP_POSITION"] = (isset($arParams["POPUP_POSITION"]) && in_array($arParams["POPUP_POSITION"], array("left",
                                                                                                                 "right"))) ? $arParams["POPUP_POSITION"] : "left";

//Подсказки
foreach($arResult["ITEMS"] as $key => $arItem) {
    $res = CIBlockProperty::GetByID($arItem['ID']);
    if($ar_res = $res->GetNext()) {
        $arResult["ITEMS"][$key]["HINT"] = $ar_res['HINT'];
    }
}

//Получить ID элементов раздела
$arItems = array();
$arFilter = array(
    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
    "ACTIVE" => "Y",
    "SECTION_ID" => $arResult["SECTION"]["ID"],
    "INCLUDE_SUBSECTIONS" => "Y"
);
$res = CIBlockElement::GetList(array('sort' => 'asc'), $arFilter, false, false, array("ID"));
while($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields();
    $arItems[] = $arFields["ID"];
}

//Получить ID разделов раздела
$arSections = array();
$arFilter = array(
    'IBLOCK_ID' => $arParams["IBLOCK_ID"],
    '<=LEFT_BORDER' => $arResult['SECTION']['LEFT_MARGIN'],
    '>=RIGHT_BORDER' => $arResult['SECTION']['RIGHT_MARGIN'],
);
$rsSect = CIBlockSection::GetList(array('left_margin' => 'asc'), $arFilter);
while($arSect = $rsSect->GetNext()) {
    $arSections[] = $arSect['ID'];
}

//Акции
$stock = true;
$arResult["STOCK"] = array();

$arFilter = array(
    "IBLOCK_ID" => 6,
    "ACTIVE_DATE" => "Y",
    "ACTIVE" => "Y",
);

if(!empty($arItems) && !empty($arSections)) {
    $arFilter[] = array(
        "LOGIC" => "OR",
        array("PROPERTY_goods" => $arItems),
        array("PROPERTY_catalog_section" => $arSections),
    );
}
elseif(!empty($arItems)) {
    $arFilter["PROPERTY_goods"] = $arItems;
}
elseif(!empty($arSections)) {
    $arFilter["PROPERTY_catalog_section"] = $arSections;
}
else {
    $stock = false;
}

if($stock) {
    $res = CIBlockElement::GetList(array('sort' => 'asc'), $arFilter, false, false, array());
    while($ob = $res->GetNextElement()) {
        $arFields = $ob->GetFields();
        $arResult["STOCK"][$arFields["ID"]] = $arFields["NAME"];
    }
}


$item_ids = array();

foreach($arResult['ITEMS'] as $arItem) {
    $item_ids[] = $arItem['ID'];
}
$prop_section_sort_result = \CUSTOM\Entity\SectionPropertySortTable::getList(array("select" => array("UF_SECTION_ID",
                                                                                                     "UF_PROPERTY_ID",
                                                                                                     "UF_SORT"),
                                                                                   "filter" => array("UF_PROPERTY_ID" => $item_ids,
                                                                                                     "UF_SECTION_ID" => array($arParams['SECTION_ID']))));

while($next = $prop_section_sort_result->fetch()) {
    $arResult['ITEMS'][$next['UF_PROPERTY_ID']]['CUSTOM_SECTION_SORT'] = $next['UF_SORT'];
}

$arResult['ITEMS'] = BXHelper::complex_sort($arResult['ITEMS'], array('CUSTOM_SECTION_SORT' => 'ASC'), false, array());

$cnt = 0;
foreach($arResult['ITEMS'] as &$arItem) {
//    echo "<pre>"; var_dump($arItem); echo "</pre>";
    $cnt++;
    if($arItem['CODE'] == 'brand') {
        $arItem['DISPLAY_SORT'] = -20;//ставим сортировку отображения так чтобы Бренды всегда вверху выводились
    }
    elseif($arItem['CODE'] == 'collection' || $arItem['CODE'] == 'seria') {
        $arItem['DISPLAY_SORT'] = -10;//ставим сортировку отображения так чтобы Бренды всегда вверху выводились
    }
    else {
        $arItem['DISPLAY_SORT'] = $cnt * 100;
    };
    if($arItem['DISPLAY_TYPE'] == "A" || $arItem['DISPLAY_TYPE'] == "RV"){
        $propertyValuesList = [];
        $dbResult = BXHelper::getSectionPropertyValues($arParams['IBLOCK_ID'], array($arParams['SECTION_ID']), [$arItem['ID']]);
        while($next = $dbResult->getNext()) {
            $propertyValuesList[] = $next;
        }
        if(count($propertyValuesList) <= 3){
            $arResult['SHOW_AS_CHECKBOXES'][$arItem['PROPERTY_ID']] = $arItem;
        }
    }
}


//дополнительная логика вывода свойств в зависимости от секций - не выводим ничего кроме бренда если Секция 2 уровня и у нее есть дочерние разделы 3го и более уровня
$section = BXHelper::getSections(array(), array('ID' => $arResult['SECTION']['ID']), false, array('ID', 'DEPTH_LEVEL'));
if($section['RESULT'][0]['DEPTH_LEVEL'] < 3 && $arResult['SECTION']['RIGHT_MARGIN'] - $arResult['SECTION']['LEFT_MARGIN'] > 0) {
    foreach($arResult['ITEMS'] as $key => $arResultItem) {
        if($arResultItem['CODE'] != 'brand' && !isset($arResultItem["PRICE"])) {
            unset($arResult['ITEMS'][$key]);
        }
    }
}

//формируем дополнительный массив в $arResult['ITEMS'] для вывода в виде чебоксов

$arStoreItem = array();
$arTransitStores = array();
$arActiveStores = array();
$selected_stores = array();
foreach($arParams["STORES"] as $arStore) {
    if($arStore['SELECTED']) {
        $selected_stores[] = $arStore['ID'];
    }
    $arActiveStores[] = $arStore['ID'];
}

$selected_active = count(array_intersect($selected_stores, $arActiveStores)) > 0;
$selected_warehouse = $arParams['SELECTED_WAREHOUSE'];
//$properties = BXHelper::getProperties(array(), array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'CODE' => 'warehouse'), array('CODE','ID'), );
//делаем пересечение массивов $stores с массивами соответствующих складов чтобы узнать какие из 2х пунктов фильтра Наличие выбраны
//$selected_transit = count(array_intersect($selected_stores, $arTransitStores)) > 0;
$arStoreValues = array(
    array(
        'CONTROL_ID' => 'arrFilter_stores_active',
        'CONTROL_NAME' => 'arrFilter_stores[]',
        'CONTROL_NAME_ALT' => 'arrFilter_stores[]',
        'HTML_VALUE_ALT' => implode(",", $arActiveStores),
        'HTML_VALUE' => implode(",", $arActiveStores),
        'VALUE' => 'Есть в наличии',
        'SORT' => 1,
        'UPPER' => 'ЕСТЬ В НАЛИЧИИ',
        'FLAG' => false,
        'CHECKED' => $selected_active ? "Y" : false
    )
);

$section_id = $arParams['SECTION_ID'];
$property_ids = array();
foreach($arResult['ITEMS'] as $p_id => $arItem) {
    if(intval($p_id)) {
        $property_ids[] = $p_id;
    }
}

$less_than_3_count_props = array();

$dbItemPropCountResult = BXHelper::getSectionPropertyLinksWithCount($arParams['IBLOCK_ID'], array($section_id), $property_ids);
while($next = $dbItemPropCountResult->getNext()) {
    if($next['VALUE_CNT'] <= 3) {
        $less_than_3_count_props[] = $next['PROPERTY_ID'];
    }
}
$arResult['FORCE_CHECKBOXES'] = array();
if(count($less_than_3_count_props)) {
    $dbResult = BXHelper::getSectionPropertyValues($arParams['IBLOCK_ID'], array($section_id), $less_than_3_count_props);
    while($next = $dbResult->getNext()) {
        $arResult['FORCE_CHECKBOXES'][$next['PROPERTY_ID']][] = $next['VALUE'];
    }
}

$arStoreItem = array(
    'ID' => 'STORES',
    'IBLOCK_ID' => false,
    'CODE' => 'STORES',
    'NAME' => 'Наличие',
    'PROPERTY_TYPE' => 'ST',
    'USER_TYPE' => false,
    'USER_TYPE_SETTINGS' => false,
    'DISPLAY_TYPE' => 'F',
    'DISPLAY_EXPANDED' => 'Y',
    'DISPLAY_SORT' => -30, //ставим сортировку отображения так чтобы Бренды всегда вверху выводились
    'VALUES' => array(
        array(
            'CONTROL_ID' => 'arrFilter_stores_active',
            'CONTROL_NAME' => 'arrFilter_stores[]',
            'CONTROL_NAME_ALT' => 'arrFilter_stores[]',
            'HTML_VALUE_ALT' => implode(",", $arActiveStores),
            'HTML_VALUE' => implode(",", $arActiveStores),
            'VALUE' => 'Есть в наличии',
            'SORT' => 1,
            'UPPER' => 'ЕСТЬ В НАЛИЧИИ',
            'FLAG' => false,
            'CHECKED' => $selected_active ? "Y" : false
        ),
        array(
            'CONTROL_ID' => 'arrFilter_stores_warehouse',
            'CONTROL_NAME' => 'arrFilter_warehouse',
            'CONTROL_NAME_ALT' => 'arrFilter_warehouse',
            'HTML_VALUE_ALT' => "Y",
            'HTML_VALUE' => "Y",
            'VALUE' => 'Складская программа',
            'SORT' => 1,
            'UPPER' => 'СКЛАДСКАЯ ПРОГРАММА',
            'FLAG' => false,
            'CHECKED' => !empty($selected_warehouse) ? "Y" : false
        )
    )
);

$filter_keys = array();
foreach($arResult['ITEMS'] as $arFilterItem) {
    $filter_keys[] = $arFilterItem['CODE'];
}

$offset = array_search('brand', $filter_keys);
array_splice($arResult['ITEMS'], $offset, 0, array($arStoreItem));
//ставим после пункта Бренда. По сути можно поставить где угодно, в любом случае массив будет отсортировать по DISPLAY_SORT

usort($arResult['ITEMS'], function($a, $b){
    if($b['DISPLAY_SORT'] == $a['DISPLAY_SORT']){
        return $a['DISPLAY_EXPANDED'] == "Y" ? 1 : 0;
    }

    return $a['DISPLAY_SORT'] > $b['DISPLAY_SORT'] ? 1 : 0;
});

//$arResult['ITEMS'] = BXHelper::complex_sort($arResult['ITEMS'], array('DISPLAY_EXPANDED' => 'DESC',
//                                                                      'DISPLAY_SORT' => 'ASC'), false);
//
