<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$APPLICATION->SetTitle($arResult["NAME"]);
//$APPLICATION->AddChainItem($arResult["NAME"]);

if(!empty($arResult['PROPERTIES']['ORGANIZER']['VALUE'])) {

    $arFilter = Array('ID' => $arResult['PROPERTIES']['ORGANIZER']['VALUE']);
    $res = CIBlockElement::GetList(Array(), $arFilter, false, false, array());

    while($ob = $res->GetNextElement()) {
        $arFields = $ob->GetFields();
        $arFields['props'] = $ob->GetProperties();
        $arResult['ORGS'][] = $arFields;
    }
}