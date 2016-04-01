<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();


//$arFilter = Array("IBLOCK_ID" => $arParams['IBLOCK_ID'], "ACTIVE" => "Y");
$arFilter = Array('SECTION_ID' => $arResult['ID']);
//$res = CIBlockElement::GetList(Array('PROPERTY_lecturer.NAME' => 'ASC'), $arFilter, array('PROPERTY_lecturer'), false, array());
$res = CIBlockElement::GetList(Array(), $arFilter, array('PROPERTY_goods'), false, array());

?>


<? while ($ob = $res->GetNextElement()): ?>

    <?$arFields = $ob->GetFields();
//    $arFields['props'] = $ob->GetProperties();
    $goods_id[] = $arFields['PROPERTY_GOODS_VALUE'];
endwhile;?>
<?
//$arFilter = Array("IBLOCK_ID" => $arParams['IBLOCK_ID'], "ACTIVE" => "Y");
$arFilter = Array('ID' => $goods_id);
//$res = CIBlockElement::GetList(Array('PROPERTY_lecturer.NAME' => 'ASC'), $arFilter, array('PROPERTY_lecturer'), false, array());
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, array());

?>


<? while ($ob = $res->GetNextElement()): ?>

    <?$arFields = $ob->GetFields();
//    $arFields['props'] = $ob->GetProperties();
    $arResult['GOODS'][] = $arFields;
    $arResult['GOODS_ELSE'][] = $arFields;

endwhile;?>
<?//var_dump($arResult['GOODS']);?>