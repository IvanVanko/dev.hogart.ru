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
