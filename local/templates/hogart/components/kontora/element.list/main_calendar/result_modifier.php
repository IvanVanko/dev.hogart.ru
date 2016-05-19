<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
/*
$arFilter = array(
	"IBLOCK_ID" => 8,

);

$res = CIBlockElement::GetList(array(), $arFilter, false, false, array());
while ($ob = $res->GetNextElement()) {
	$arFields = $ob->GetFields();
	$arResult['SEMINARS'][] = $arFields['PROPERTY_TYPE_VALUE'];
}*/

$arSelect = Array("ID", "IBLOCK_ID", "NAME","IBLOCK_TYPE", "IBLOCK_NAME", "DETAIL_PAGE_URL", "PROPERTY_sem_start_date");
$arFilter = Array("IBLOCK_ID"=> $arParams["IBLOCK_ID"], "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>1000), $arSelect);
//while($ob = $res->GetNextElement())
while($ob = $res->GetNext())
{
//    $arFields = $ob->GetFields();
    $arResult['SEMINARS'][] = $ob;
}
//var_dump($arResult['SEMINARS']);