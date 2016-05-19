<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

$arSelect = Array("ID", "IBLOCK_ID", "NAME","IBLOCK_TYPE", "IBLOCK_NAME", "DETAIL_PAGE_URL", "PROPERTY_sem_start_date");
$arFilter = Array("IBLOCK_ID"=> (LANGUAGE_ID == 'en' ? '39' : '8'), "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>1000), $arSelect);
while($ob = $res->GetNext())
{
    $arResult['SEMINARS'][] = $ob;
}