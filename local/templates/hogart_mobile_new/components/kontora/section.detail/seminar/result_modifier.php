<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arFilter = Array("IBLOCK_ID" => 9, "ACTIVE" => "Y", 'ID' => array_merge($arResult['PROPERTIES']['org']['VALUE'], $arResult['PROPERTIES']['lecturer']['VALUE']));
//$res = CIBlockElement::GetList(Array('PROPERTY_lecturer.NAME' => 'ASC'), $arFilter, array('PROPERTY_lecturer'), false, array());
$res = CIBlockElement::GetList(Array('PROPERTY_lecturer.NAME' => 'ASC', 'PROPERTY_lecturer.status' => 'ASC'), $arFilter, false, false, array());

 while ($ob = $res->GetNextElement()){

    $arFields = $ob->GetFields();
    $arFields['props'] = $ob->GetProperties();


    if ($arFields['ID'] == $arResult['PROPERTIES']['org']['VALUE']){
        $arResult['ORGS'][] = $arFields;
    } else {
        $arResult['LECTORS'][] = $arFields;
    }


}?>
<?

$nav = array();
$arSelect = Array("ID", "NAME", 'DETAIL_PAGE_URL');
$arFilter = Array("IBLOCK_ID" => 8);
$res = CIBlockElement::GetList(array("PROPERTY_time" => "ASC"), $arFilter, false, Array("nElementID" => $arParams['ID'], 'nPageSize' => 1), $arSelect);
while($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields();
    $nav[] = $arFields;

}

if ($nav[0]['ID'] != $arParams['ID'])
    $arResult['PREV'] = $nav[0]['DETAIL_PAGE_URL'];

if ($nav[count($nav)-1]['ID'] != $arParams['ID'])
    $arResult['NEXT'] = $nav[count($nav)-1]['DETAIL_PAGE_URL'];

?>