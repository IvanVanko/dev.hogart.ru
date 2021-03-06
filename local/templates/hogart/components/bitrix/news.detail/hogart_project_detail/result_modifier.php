<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
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

$sections = BXHelper::getSections(array("SORT" => "ASC", "ID" => "ASC"), array("IBLOCK_ID" => (LANGUAGE_ID == 'en' ? 35 : 7)), false, array("ID",
                                                                                                               "CODE"), true, false);
$sections = $sections['RESULT'];
$arResult['ACTIVE_SECTION_ID'] = $sections_id = BXHelper::pull_array_field($sections, 'ID');

$elements_with_solution = BXHelper::getElements(array(), array('IBLOCK_ID' => (LANGUAGE_ID == 'en' ? 37 : 18),
                                                               "SECTION_ID" => $sections_id), false, false, array('ID',
                                                                                                                  'PROPERTY_solution_id'), true, 'ID');

$solutions = BXHelper::pull_array_field($elements_with_solution['RESULT'], 'PROPERTY_SOLUTION_ID_VALUE');


$nav = array();
$arSelect = Array("ID", "NAME", 'CODE', 'DETAIL_PAGE_URL', "PROPERTY_solution_id",);
$arFilter = Array("IBLOCK_ID" => (LANGUAGE_ID == 'en' ? 37 : 18), "ACTIVE" => "Y", "PROPERTY_solution_id" => $arResult['PROPERTIES']['solution_id']['VALUE']);

$sections = BXHelper::getSections(array("SORT" => "ASC", "ID" => "ASC"), array("IBLOCK_ID" => (LANGUAGE_ID == 'en' ? 35 : 7),
                                                                               "ID" => $solutions), false, array("ID",
                                                                                                                 "CODE"), true, false);
$sections = $sections['RESULT'];

$sections_by_id = array();

foreach($sections as $sect) {
    $sections_by_id[$sect['ID']] = $sect;
}

$next_section_id = false;
$prev_section_id = false;
$count_sections = count($sections);

foreach($sections as $key => $sect) {
    if($sect['ID'] == $arResult['PROPERTIES']['solution_id']['VALUE']) {
        if($key) {
            $prev_section_id = $sections[$key - 1]['ID'];
        }
        if($count_sections - ($key + 1)) {
            $next_section_id = $sections[$key + 1]['ID'];
        }
        break;
    };
}

if(empty($prev_section_id)) {
    $prev_section_id = $sections[$count_sections - 1]['ID'];
}
if(empty($next_section_id)) {
    $next_section_id = $sections[0]['ID'];
}

$res = CIBlockElement::GetList(array("PROPERTY_solution_id" => "ASC",
                                     "SORT" => "ASC",
                                     "NAME" => "ASC",
                                     "ID" => "ASC"), $arFilter, false, Array("nElementID" => $arResult['ID'],
                                                                             "nPageSize" => 1), $arSelect);
$elements = array();
while($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields();
    $nav[] = $arFields;
    $elements[$arFields['ID']] = $arFields;
}

$valid_nav_ids = array();
$count_siblings = count($nav);
foreach($nav as $key => $nav_element) {
    if($nav_element['ID'] == $arResult['ID']) {
        if($key) {
            $valid_nav_ids[$key - 1] = $nav[$key - 1]['ID'];
        }
        if($count_siblings - ($key + 1)) {
            $valid_nav_ids[$key + 1] = $nav[$key + 1]['ID'];
        }
    }
}

if(count($valid_nav_ids) > 1) {
    $valid_nav_ids = array_values($valid_nav_ids);
}


if(empty($valid_nav_ids[0]) && !empty($prev_section_id)) {
    $prev_element = BXHelper::getElements(array("SORT" => "DESC",
                                                "NAME" => "DESC",
                                                "ID" => "DESC"), array("IBLOCK_ID" => (LANGUAGE_ID == 'en' ? 37 : 18),
                                                                       "PROPERTY_solution_id" => $prev_section_id), false, array('nTopCount' => 1), array("ID",
                                                                                                                                                          "CODE",
                                                                                                                                                          "PROPERTY_solution_id"));
    $prev_element = $prev_element['RESULT'][0];
    if(!empty($prev_element)) {
        $elements[$prev_element['ID']] = $prev_element;
        $valid_nav_ids[0] = $prev_element['ID'];
    }
}

if(empty($valid_nav_ids[1]) && !empty($next_section_id)) {
    $next_element = BXHelper::getElements(array("SORT" => "ASC",
                                                "NAME" => "ASC",
                                                "ID" => "ASC"), array("IBLOCK_ID" => (LANGUAGE_ID == 'en' ? 37 : 18),
                                                                      "ACTIVE" => "Y", "CHECK_PERMISSIONS" => "Y",
                                                                      "PROPERTY_solution_id" => $next_section_id), false, array('nTopCount' => 1), array("ID",
                                                                                                                                                         "CODE",
                                                                                                                                                         "PROPERTY_solution_id"));
    $next_element = $next_element['RESULT'][0];
    if(!empty($next_element)) {
        $elements[$next_element['ID']] = $next_element;
        $valid_nav_ids[1] = $next_element['ID'];
    }
}

if(!empty($valid_nav_ids[0])) {
    $arResult['PREV'] = $arParams['SEF_FOLDER'].$sections_by_id[$elements[$valid_nav_ids[0]]['PROPERTY_SOLUTION_ID_VALUE']]['CODE']."/".$elements[$valid_nav_ids[0]]['CODE']."/";
}

if(!empty($valid_nav_ids[1])) {
    $arResult['NEXT'] = $arParams['SEF_FOLDER'].$sections_by_id[$elements[$valid_nav_ids[1]]['PROPERTY_SOLUTION_ID_VALUE']]['CODE']."/".$elements[$valid_nav_ids[1]]['CODE']."/";
}
