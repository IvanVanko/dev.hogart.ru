<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$arFilter = Array("IBLOCK_ID" => (LANGUAGE_ID == 'en' ? 40 : 9),
                  "ACTIVE" => "Y",
                  'ID' => array_merge($arResult['PROPERTIES']['org']['VALUE'], $arResult['PROPERTIES']['lecturer']['VALUE']));
$res = CIBlockElement::GetList(Array('PROPERTY_lecturer.NAME' => 'ASC',
                                     'PROPERTY_lecturer.status' => 'ASC'), $arFilter, false, false, array());

while($ob = $res->GetNextElement()) {

    $arFields = $ob->GetFields();
    $arFields['props'] = $ob->GetProperties();


    if($arFields['ID'] == $arResult['PROPERTIES']['org']['VALUE']) {
        $arResult['ORGS'] = $arFields;
    }
    if(in_array($arFields['ID'], $arResult['PROPERTIES']['lecturer']['VALUE'])) {
        $arResult['LECTORS'][] = $arFields;
    }

} ?>
<?

$nav = array();
$arSelect = Array("ID", "NAME", 'DETAIL_PAGE_URL');
$arFilter = Array("IBLOCK_ID" => $arParams['IBLOCK_ID'], "ACTIVE" => "Y");

//Проверка архивный или нет
$sem_start_date = strtotime(FormatDate("d.m.Y", MakeTimeStamp($arResult['PROPERTIES']['sem_start_date']['VALUE'])));
$sem_start_date = (!empty($sem_start_date)) ? $sem_start_date : 0;
$now = strtotime(date($DB->DateFormatToPHP(CSite::GetDateFormat("SHORT")), time()));

$arOrder = array("PROPERTY_sem_start_date" => "DESC");
if (empty($arResult["PROPERTIES"]["sem_start_date"]["VALUE"])) {
    $arOrder = array("ID" => "ASC");
}
$arFilter["ACTIVE"] = "Y";
$arFilter["ACTIVE_DATE"] = "Y";
$arFilter["CHECK_PERMISSIONS"] = "Y";

if($sem_start_date < $now && $sem_start_date > 0) {
    $arFilter['<PROPERTY_sem_start_date'] = date("Y-m-d", time());
    $arFilter[] = array(
        'LOGIC' => 'OR',
        array('PROPERTY_sem_end_date' => false),
        array('<PROPERTY_sem_end_date' => date("Y-m-d", time()))
    );
}
elseif($sem_start_date > 0) {
    $arFilter['>=PROPERTY_sem_start_date'] = date("Y-m-d", time());
} else {
    $arFilter["PROPERTY_sem_start_date"] = false;
}

$res = CIBlockElement::GetList($arOrder, $arFilter, false, Array("nElementID" => $arResult['ID'], 'nPageSize' => 1), $arSelect);

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
else {
    if($nav[0]['ID'] != $arParams['ID']) {
        $arResult['PREV'] = $nav[0]['DETAIL_PAGE_URL'];
    }

    if($nav[count($nav) - 1]['ID'] != $arParams['ID']) {
        $arResult['NEXT'] = $nav[count($nav) - 1]['DETAIL_PAGE_URL'];
    }
}
