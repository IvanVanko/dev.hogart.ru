<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
#return;
$arResult['USER'] = '';
if ($USER->IsAuthorized()) {
	$rsUser = CUser::GetByID($USER->GetID());
	$arUser = $rsUser->Fetch();
	$arResult['USER'] = $arUser;
}

//$arFilter = Array("IBLOCK_ID" => $arParams['IBLOCK_ID'], "ACTIVE" => "Y");
$arFilter = Array('IBLOCK_ID' => $arParams['IBLOCK_ID']);
$res = CIBlockElement::GetList(
    array('ID' => 'DESC'),
//    array(),
    $arFilter,
    false,
    false,
    array()
);

while ($ob = $res->GetNextElement()){

    $arFields = $ob->GetFields();
    $arFields = $ob->GetProperties();
    $arResult['COMMS'][] = $arFields;
}
?>