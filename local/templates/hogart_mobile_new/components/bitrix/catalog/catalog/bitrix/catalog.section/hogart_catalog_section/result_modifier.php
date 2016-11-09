<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

if ($arResult['DEPTH_LEVEL'] > 2) {
    $arFilter = array('IBLOCK_ID' => $arParams["IBLOCK_ID"], 'ID' => $arResult["IBLOCK_SECTION_ID"]);
    $rsSect = CIBlockSection::GetList(array('left_margin' => 'asc'), $arFilter);
    if ($arSect = $rsSect->GetNext()) {
        $arResult['PARENT_PARENT_SECTION_ID'] = $arSect['IBLOCK_SECTION_ID'];
    }
}
//echo '<pre>';
//var_dump($arResult['PARENT_PARENT_SECTION_ID']);
//echo '</pre>';
if (!empty($arParams['HREF_BRAND_CODE'])) {

    //делаем маппинг для дочерних классов представляющие разделы Хогарт
    //необходимо для задания шаблонов для брендированных разделов согласно SEO требованиям, которые не удалось сделать на основе SEO модуля
    //section field map - функция отдающая стандартные поля разделов необходимые для генерации  seo шаблонов
    //делаем это для того, чтобы вытянуть все данные для meta в один getlist

    //договоримся, что

    //SectionMain - раздел первого уровня
    //SectionSecondary - раздел второго уровня
    //SectionMinor - раздел третьего уровня

    function section_field_map () {
        return array(
            'ID' => array(
                'data_type' => 'integer',
                'primary' => true,
                'autocomplete' => true,
                'title' => "ID раздела",
            ),
            'DEPTH_LEVEL' => array(
                'data_type' => 'integer',
                'title' => "Глубина раздела",
            ),
            'LEFT_MARGIN' => array(
                'data_type' => 'integer',
                'title' => "Левая",
            ),
            'RIGHT_MARGIN' => array(
                'data_type' => 'integer',
                'title' => "Глубина раздела",
            ),
            'CODE' => array(
                'data_type' => 'string',
                'title' => "Код",
            ),
            'NAME' => array(
                'data_type' => 'string',
                'title' => "Название",
            ),
            'IBLOCK_ID' => array(
                'data_type' => 'integer',
                'required' => true,
                'title' => "ID инфоблока раздела",
            ),
            'IBLOCK_SECTION_ID' => array(
                'data_type' => 'integer',
                'title' => "ID родительской секции",
            ),
        );
    };


    class SectionMainTable extends  Bitrix\Iblock\SectionTable {
        //по факту оказалась не нужна здесь. пока оставил, в зарпросе все равно не изспользуетсяы
        public static function getMap() {
            return section_field_map();
        }
    }

    class SectionSecondaryTable extends  Bitrix\Iblock\SectionTable {
        public static function getMap() {
            return array_merge(
                section_field_map(),
                array(
                    'PARENT_SECTION' => array(
                        'data_type' => 'SectionMain',
                        'reference' => array('=this.IBLOCK_SECTION_ID' => 'ref.ID'),
                        //связка с SectionMain
                    ),
                )
            );
        }
    }

    class SectionMinorTable extends Bitrix\Iblock\SectionTable {
        public static function getMap() {
            return array_merge(
                section_field_map(),
                array(
                    'PARENT_SECTION' => array(
                        'data_type' => 'SectionSecondary',
                        'reference' => array('=this.IBLOCK_SECTION_ID' => 'ref.ID'),
                        //связка с SectionSecondary
                    ),
                )
            );
        }
    }
    //если этот параметр задан, значи мы находится в разделе каталога по определенному бренду
    $brand_elements = BXHelper::getElements(array(), array('IBLOCK_ID' => BRAND_IBLOCK_ID, 'CODE' => $arParams['HREF_BRAND_CODE']), false, false, array('ID','CODE','NAME'), true, 'CODE');
    $brand_element_name = $brand_elements['RESULT'][$arParams['HREF_BRAND_CODE']]['NAME'];
    $arResult['NAME'] .= " ".$brand_element_name;
    if ($arResult['DEPTH_LEVEL'] == '2') {
        $ar_minor_section_names = array();
        $dbSectionResult = SectionMinorTable::getList(
            array(
                'select' => array(
                    'ID',
                    'NAME',
                    'CODE',
                    'LEFT_MARGIN',
                    //'PARENT_ID' => 'PARENT_SECTION.ID',
                    'PARENT_NAME' => 'PARENT_SECTION.NAME'
                ),
                'filter' => array(
                    'PARENT_SECTION.ID' => $arResult['ID']
                ),
                'order' => array(
                    'LEFT_MARGIN' => 'ASC'
                )
            )
        );
        while ($next = $dbSectionResult->fetch()) {

            if (!isset($ar_minor_section_names[$next['NAME']])) {
                $ar_minor_section_names[$next['NAME']] = $next['NAME'];
            }
        }
        $arResult['CUSTOM_META']['keywords'] = $arResult['NAME']." ".$brand_element_name." ".implode(" ", $ar_minor_section_names);
    } else if ($arResult['DEPTH_LEVEL'] == '3') {
        $arResult['CUSTOM_META']['keywords'] = $arResult['NAME']." ".$brand_element_name;
    }
    $arResult['CUSTOM_META']['title'] = $arResult['NAME']." ".$brand_element_name." - цены, характеристики, условия доставка | Hogart";
    $arResult['CUSTOM_META']['description'] = $arResult['NAME']." ".$brand_element_name.". Hogart - поставщик более 100 производителей высокотехнологичных инжереных система. На рынке с 1996 года";
}
$section_ids = array();
$property_ids = array();
foreach ($arResult['ITEMS'] as &$arItem) {

    $elements_ids[] = $arItem['ID'];
    $section_ids[] = $arItem['~IBLOCK_SECTION_ID'];
    foreach ($arItem['PROPERTIES'] as $arDispProp) {
        $property_ids[] = $arDispProp['ID'];
    }
    $brand_id = $arItem['DISPLAY_PROPERTIES']['brand']['VALUE'];
    $brand_code = $arItem['DISPLAY_PROPERTIES']['brand']['LINK_ELEMENT_VALUE'][$brand_id]['CODE'];
    if (!empty($brand_code)) {
        $exploded_url = explode("/",$arItem['DETAIL_PAGE_URL']);
        $c = count($exploded_url);
        array_splice( $exploded_url, $c-2, 0, array($brand_code) );
        $arItem['DETAIL_PAGE_URL'] = implode("/",$exploded_url);
    }
}


//список соседних разделов
$arFilter = array(
    'IBLOCK_ID' => $arParams["IBLOCK_ID"],
    'SECTION_ID' => $arResult["IBLOCK_SECTION_ID"],
);
$rsSect = CIBlockSection::GetList(array('NAME' => 'ASC'),$arFilter, false, array('NAME','ID','CODE','SECTION_PAGE_URL'));
while ($arSect = $rsSect->GetNext())
{
    $arResult["NEIB"][] =$arSect;
}
$arFilter = array(
    'IBLOCK_ID' => $arParams["IBLOCK_ID"],
    'SECTION_ID' => $arResult["ID"],
);
$rsParentSection = CIBlockSection::GetList(array('NAME' => 'ASC'), $arFilter, false, array('NAME','ID','CODE','SECTION_PAGE_URL'));

    while ($arSect = $rsParentSection ->GetNext())
    {
        
        $count = CIBlockElement::GetList(Array(), array('IBLOCK_ID' => $arParams["IBLOCK_ID"],'SECTION_ID' => $arSect["ID"]), Array());
        if ($count>0)
        $arResult["SUBS"][] = $arSect;
    }


$arFilterBrands = array(
    'IBLOCK_ID' => 2
);
$arSelectBrands = Array("ID", "NAME", 'DETAIL_PAGE_URL');
$arBrands = CIBlockElement::GetList(array(), $arFilterBrands, false, false, false, $arSelectBrands);

while ($res = $arBrands ->GetNext())
{
    $arResult["ALL_BRANDS"][$res['ID']] = array('NAME' => $res['NAME'],'DETAIL_PAGE_URL' => $res['DETAIL_PAGE_URL'], );
//    $arResult["ALL_BRANDS"][] = $res;
}
$arFilterColls = array(
    'IBLOCK_ID' => 22
);
$arSelectColls = Array("ID", "NAME", 'DETAIL_PAGE_URL');
$arColls = CIBlockElement::GetList(array(), $arFilterColls, false, false, false, $arSelectColls);

while ($res = $arColls ->GetNext())
{
    $arResult["ALL_COLLS"][$res['ID']] = array('NAME' => $res['NAME']);
}
//echo '<pre>';
//var_dump($arResult["ALL_COLLS"]);
//echo '</pre>';
$section_ids = array_unique($section_ids);

if ($arParams['STORES_FILTERED'] != 'Y') {
    $stores = BXHelper::getStores(array(), array(), false, false, array('ID'));
    $catalog_store_filter = array('LOGIC' => 'OR');
    foreach ($stores as $store_id) {
        $store_keys[] = 'CATALOG_STORE_AMOUNT_'.$store_id['ID'];
        $catalog_store_filter[] = array('>CATALOG_STORE_AMOUNT_'.$store_id['ID'] => 0);
    }
    $custom_filter[] = $catalog_store_filter;
    $custom_filter['ID'] = $elements_ids;
    $elements = BXHelper::getElements(array(), $custom_filter, false, false, array('ID'), true, 'ID');
    foreach ($arResult['ITEMS'] as &$arItem) {
        $arItem['CATALOG_QUANTITY'] = 0;
        if (isset($elements['RESULT'][$arItem['ID']])) {
            foreach ($store_keys as $s_key) {
                $arItem[$s_key] +=intval($elements['RESULT'][$arItem['ID']][$s_key]);
            }
        }
    }
}


$property_ids = array_unique($property_ids);
$prop_section_sort_result = \CUSTOM\Entity\SectionPropertySortTable::getList(array("select" => array("UF_SECTION_ID", "UF_PROPERTY_ID","UF_SORT"), "filter" => array("UF_PROPERTY_ID" => $property_ids, 'UF_SECTION_ID' => $section_ids)));

while ($next = $prop_section_sort_result->fetch()) {
    $section_prop_sort[] = $next;
}


$section_properties = BXHelper::getPropertySectionLinks($arParams['IBLOCK_ID'], $section_ids, 3, 3, array(), array('SMART_FILTER' => 'Y'));
foreach ($arResult['ITEMS'] as $i => &$arCollItem) {
    if (!isset($store_keys)) {
        $store_keys = preg_grep("/^CATALOG_STORE_AMOUNT_/",array_keys($arCollItem));//подсчитываем кол-во товара только по отфильтрованным складам.
    }
    if (!empty($store_keys)) {
        $arCollItem['CATALOG_QUANTITY'] = 0;
        foreach ($store_keys as $s_key) {
            $arCollItem['CATALOG_QUANTITY'] +=intval($arCollItem[$s_key]);
        }
    }
    //$section_properties = CIBlockSectionPropertyLink::GetArray(1, $arCollItem['IBLOCK_SECTION_ID']);
    $result_properties = array();
    foreach ($section_properties as $key => $value) {
        if ($value['ID'] == $arCollItem['~IBLOCK_SECTION_ID'] && array_key_exists($value['CODE'], $arCollItem['PROPERTIES'])) {
            if (!empty($arCollItem['PROPERTIES'][$value['CODE']])) {
                $arCollItem['PROPERTIES'][$value['CODE']] = array_merge($arCollItem['PROPERTIES'][$value['CODE']], $value);
                foreach ($section_prop_sort as $arSort) {
                    if ($arSort['UF_PROPERTY_ID'] == $arCollItem['PROPERTIES'][$value['CODE']]['PROPERTY_ID']) {
                        $arCollItem['PROPERTIES'][$value['CODE']]['CUSTOM_SECTION_SORT'] = $arSort['UF_SORT'];
                    }
                }
                $arCollItem['PROPERTIES'][$value['CODE']]['DISPLAY_EXPANDED_SORT'] = intval($arCollItem['PROPERTIES'][$value['CODE']]["DISPLAY_EXPANDED"] == "Y")*100;
            } else {
                unset($arCollItem['PROPERTIES'][$value['CODE']]);
            }
        }
    }
    $arCollItem['PROPERTIES'] = BXHelper::complex_sort($arCollItem['PROPERTIES'], array('DISPLAY_EXPANDED_SORT' => 'DESC', 'CUSTOM_SECTION_SORT' => 'ASC'), false);
    HogartHelpers::mergeRangePropertiesForItem($arCollItem['PROPERTIES']);
}



BXHelper::addCachedKeys($this->__component, array(
    'ELEMENTS',
    'ALL_COLLS',
    'NEIB',
    'SUBS',
    'PARENT_PARENT_SECTION_ID',
    'DEPTH_LEVEL',
    'CUSTOM_META'
), $arResult);


$parentSection = BXHelper::getSectionPath($arResult["IBLOCK_ID"], $arResult["IBLOCK_SECTION_ID"], array());
$parentSectionID = $parentSection["RESULT"][0]["ID"];

$arFilter = Array('IBLOCK_ID' => EQUIPMENT_SELECTION_IBLOCK_ID, 'ACTIVE' => 'Y', 'UF_CATALOG_SECTION' => $parentSectionID);
$db_list = CIBlockSection::GetList(Array($by => $order), $arFilter, true);
$arResult["eqSelectID"] = false;
if ($ar_result = $db_list->GetNext()) {
    $arResult["eqSelectID"] = $ar_result["ID"];
}

   $db_list = CIBlockSection::GetList(Array("SORT" => "ASC"), $arFilter = Array("IBLOCK_ID" => $arResult["IBLOCK_ID"], "ID" => $arResult["ID"]), true, $arSelect = Array("UF_*"));
    while ($ar_result = $db_list->GetNext()) {
        foreach ($ar_result as $key => $prop) {
            if (strpos($key, "UF_") === 0 || strpos($key, "~UF_") === 0)
                $arResult[$key] = $prop;
        }
    }
