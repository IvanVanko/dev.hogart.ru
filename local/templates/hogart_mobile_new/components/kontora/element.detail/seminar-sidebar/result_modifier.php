<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (!empty($arResult['PROPERTIES']['org']['VALUE'])) {

$arFilter = Array("IBLOCK_ID" => 9, "ACTIVE" => "Y", 'ID' => $arResult['PROPERTIES']['org']['VALUE']);
$res = CIBlockElement::GetList(Array('PROPERTY_lecturer.NAME' => 'ASC', 'PROPERTY_lecturer.status' => 'ASC'), $arFilter, false, false, array());


while ($ob = $res->GetNextElement()) {

	$arFields = $ob->GetFields();
	$arFields['props'] = $ob->GetProperties();
	$arResult['ORGS'] = $arFields;

}
}?>
