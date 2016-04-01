<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$nav = array();
$arSelect = Array("ID", "NAME", 'DETAIL_PAGE_URL');
$arFilter = Array("IBLOCK_ID" => $arParams['IBLOCK_ID'], "ACTIVE" => "Y", "CHECK_PERMISSIONS" => "Y");
$res = CIBlockElement::GetList($arParams['ORDER'], $arFilter, false, Array("nElementID" => $arResult['ID'],
                                                                           'nPageSize' => 1), $arSelect);
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
elseif(count($nav) == 3) {
    if($nav[0]['ID'] != $arParams['ID']) {
        $arResult['PREV'] = $nav[0]['DETAIL_PAGE_URL'];
    }

    if($nav[count($nav) - 1]['ID'] != $arParams['ID']) {
        $arResult['NEXT'] = $nav[count($nav) - 1]['DETAIL_PAGE_URL'];
    }
}