<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();


//$arFilter = Array("IBLOCK_ID" => $arParams['IBLOCK_ID'], "ACTIVE" => "Y");
$arFilter = Array('ID' => $arResult['PROPERTIES']['goods']['VALUE']);
//$res = CIBlockElement::GetList(Array('PROPERTY_lecturer.NAME' => 'ASC'), $arFilter, array('PROPERTY_lecturer'), false, array());
$res = CIBlockElement::GetList(Array('PROPERTY_goods.NAME' => 'ASC'), $arFilter, false, false, array());

?>


<? while ($ob = $res->GetNextElement()): ?>

    <?$arFields = $ob->GetFields();
    $arFields['props'] = $ob->GetProperties();
    $arResult['GOODS'][] = $arFields;

//    var_dump($arFields);

endwhile;?>

<?
$elements_with_solution = BXHelper::getElements(array(), array('IBLOCK_ID' => 18, "!PROPERTY_solution_id" => false), false, false, array('ID', 'PROPERTY_solution_id'), true, 'PROPERTY_SOLUTION_ID_VALUE');
$solutions = array_keys($elements_with_solution['RESULT']);


$nav = array();
$arSelect = Array("ID", "NAME", 'DETAIL_PAGE_URL', "PROPERTY_solution_id",);
$arFilter = Array("IBLOCK_ID" => 18, "PROPERTY_solution_id" => $arResult['PROPERTIES']['solution_id']['VALUE']);

$sections = BXHelper::getSections(array("SORT" => "ASC", "ID" => "ASC"), array("IBLOCK_ID" => 7, "ACTIVE" => "Y", "ID" => $solutions), false, array("ID","CODE"), true, false);
$sections = $sections['RESULT'];
$sections_by_id = array();
foreach ($sections as $sect) {
    $sections_by_id[$sect['ID']] = $sect;
}


$next_section_id = false;
$prev_section_id = false;
$count_sections = count($sections);

foreach ($sections as $key => $sect) {
    if ($sect['ID'] == $arResult['PROPERTIES']['solution_id']['VALUE']) {
        if ($key) {
            $prev_section_id = $sections[$key-1]['ID'];
        }
        if ($count_sections - ($key + 1)) {
            $next_section_id = $sections[$key+1]['ID'];
        }
        break;
    };
}


$res = CIBlockElement::GetList(array("PROPERTY_solution_id" => "ASC", "ID" => "ASC"), $arFilter, false, Array("nElementID" => $arParams['ID'], 'nPageSize' => 1), $arSelect );
$elements = array();
while($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields();
    $nav[] = $arFields;
    $elements[$arFields['ID']] = $arFields;
}



$valid_nav_ids = array();
$count_siblings = count($nav);
foreach ($nav as $key => $nav_element) {
    if ($nav_element['ID'] == $arResult['ID']) {
        if ($key) {
            $valid_nav_ids[$key-1] = $nav[$key-1]['ID'];
        }
        if ($count_siblings - ($key + 1)) {
            $valid_nav_ids[$key+1] = $nav[$key+1]['ID'];
        }
    }
}

if (count($valid_nav_ids) > 1) {
    $valid_nav_ids = array_values($valid_nav_ids);
}



if (empty($valid_nav_ids[0]) && !empty($prev_section_id)) {
    $prev_element = BXHelper::getElements(array("ID" => "DESC"), array("IBLOCK_ID" => 18, "PROPERTY_solution_id" => $prev_section_id), false, false, array("ID", "PROPERTY_solution_id"));
    $prev_element = $prev_element['RESULT'][0];
    if (!empty($prev_element)) {
        $elements[$prev_element['ID']] = $prev_element;
        $valid_nav_ids[0] = $prev_element['ID'];
    }
}

if (empty($valid_nav_ids[1]) && !empty($next_section_id)) {
    $next_element = BXHelper::getElements(array("ID" => "ASC"), array("IBLOCK_ID" => 18, "PROPERTY_solution_id" => $next_section_id), false, array('nTopCount' => 1), array("ID", "PROPERTY_solution_id"));
    $next_element = $next_element['RESULT'][0];
    if (!empty($next_element)) {
        $elements[$next_element['ID']] = $next_element;
        $valid_nav_ids[1] = $next_element['ID'];
    }
}

if (!empty($valid_nav_ids[0])) {
    $arResult['PREV'] = "/".$arParams['SEF_FOLDER']."/".$sections_by_id[$elements[$valid_nav_ids[0]]['PROPERTY_SOLUTION_ID_VALUE']]['CODE']."/".$elements[$valid_nav_ids[0]]['ID']."/";
}

if (!empty($valid_nav_ids[1])) {
    $arResult['NEXT'] = "/".$arParams['SEF_FOLDER']."/".$sections_by_id[$elements[$valid_nav_ids[1]]['PROPERTY_SOLUTION_ID_VALUE']]['CODE']."/".$elements[$valid_nav_ids[1]]['ID']."/";
}


//$page = $GLOBALS['APPLICATION']->getCurDir();
//$page = explode('/', $page);
//
//$pagePrev = '/'.$page[1].'/'.$page[2].'/'.$nav[0]['ID'].'/';
//$pageNext = '/'.$page[1].'/'.$page[2].'/'.$nav[count($nav)-1]['ID'].'/';

//echo $pagePrev.'<br>';
//echo $pageNext.'<br>';
//
//echo '<pre>';
//var_dump($page);
//echo '</pre>';
//echo $nav[0]['ID'].'<br>';
//echo $nav[count($nav)-1]['ID'].'<br>';
//if ($nav[0]['ID'] != $arParams['ID'])
//    $arResult['PREV'] = $pagePrev;
//
//if ($nav[count($nav)-1]['ID'] != $arParams['ID'])
//    $arResult['NEXT'] = $pageNext;

?>