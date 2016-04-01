<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
foreach ($arResult['SECTIONS'] as $k => $arSect) {
    $arResult['SECTIONS'][$k]['PICTURE']['SRC'] = BXHelper::getResizedPictureByName(
        $arResult['SECTIONS'][$k]['PICTURE']['SRC'], array('width' => 101, 'height' => 101), BX_RESIZE_IMAGE_PROPORTIONA
    );
}
$nav = array();
$arSelect = array("ID", "NAME", 'DETAIL_PAGE_URL');
$arFilter = array("IBLOCK_ID" => $arParams['IBLOCK_ID']);
$res = CIBlockElement::GetList($arParams['ORDER'], $arFilter, false, array("nElementID" => $arParams['ID'], 'nPageSize' => 1), $arSelect);
while($ob = $res->GetNextElement()) {
	$arFields = $ob->GetFields();
	$nav[] = $arFields;
}

if ($nav[0]['ID'] != $arParams['ID'])
	$arResult['PREV'] = $nav[0]['DETAIL_PAGE_URL'];

if ($nav[count($nav)-1]['ID'] != $arParams['ID'])
	$arResult['NEXT'] = $nav[count($nav)-1]['DETAIL_PAGE_URL'];

foreach ($arResult['SECTIONS'] as $k => $arSection) {
    if (!intval($arSection['ELEMENT_CNT'])) {
        unset($arResult['SECTIONS'][$k]);
    }
}