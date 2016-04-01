<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$arFilter = Array("IBLOCK_ID" => 9, "ACTIVE" => "Y", 'ID' => $arResult['PROPERTIES']['sec_face']['VALUE']);

$res = CIBlockElement::GetList(Array('PROPERTY_lecturer.NAME' => 'ASC',
                                     'PROPERTY_lecturer.status' => 'ASC'), $arFilter, false, false, array("NAME",
                                                                                                          "PREVIEW_PICTURE",
                                                                                                          "PROPERTY_status",
                                                                                                          "PROPERTY_company",
                                                                                                          "PROPERTY_phone",
                                                                                                          "PROPERTY_mail"));

while($ob = $res->GetNextElement()) {

    $arFields = $ob->GetFields();
    $arFields["PREVIEW_PICTURE"] = CFile::GetPath($arFields["PREVIEW_PICTURE"]);
    $arResult['LECTORS'] = $arFields;
}
