<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
foreach($arResult['SECTIONS'] as $k => $arSect) {
    $arResult['SECTIONS'][$k]['PICTURE']['SRC'] = BXHelper::getResizedPictureByName(
        $arResult['SECTIONS'][$k]['PICTURE']['SRC'], array('width' => 101, 'height' => 101), BX_RESIZE_IMAGE_PROPORTIONA
    );
}
$nav = array();
$arSelect = array("ID", "NAME", 'DETAIL_PAGE_URL');
$arFilter = array("IBLOCK_ID" => $arParams['IBLOCK_ID'], "ACTIVE" => "Y", "CHECK_PERMISSIONS" => "Y");
$res = CIBlockElement::GetList($arParams['ORDER'], $arFilter, false, array("nElementID" => $arParams['ID'],
                                                                           'nPageSize' => 1), $arSelect);
while($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields();
    $nav[] = $arFields;
}

if(count($nav) == 2){
    if($nav[0]['ID'] == $arResult['ID']){
        $arResult['NEXT'] = $nav[0]['DETAIL_PAGE_URL'];
    }
    else {
        $arResult['PREV'] = $nav[0]['DETAIL_PAGE_URL'];
    }
}
else {
    if($nav[0]['ID'] != $arParams['ID']) {
        $arResult['PREV'] = $nav[0]['DETAIL_PAGE_URL'];
    }

    if($nav[count($nav) - 1]['ID'] != $arParams['ID']) {
        $arResult['NEXT'] = $nav[count($nav) - 1]['DETAIL_PAGE_URL'];
    }
}

foreach($arResult['SECTIONS'] as $k => $arSection) {
    if(!intval($arSection['ELEMENT_CNT'])) {
        unset($arResult['SECTIONS'][$k]);
    }
}

foreach($arResult['SECTIONS'] as $section){
    $mas=array();
    if($section['DEPTH_LEVEL']==3) {
        $arSelect = Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_brand");//IBLOCK_ID и ID обязательно должны быть указаны, см. описание arSelectFields выше
        $arFilter = Array("IBLOCK_ID" => 1, "SECTION_ID" => $section['ID']);
        $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
        while ($ob = $res->Fetch()) {
            $mas[$ob['PROPERTY_BRAND_VALUE']]=$ob['PROPERTY_BRAND_VALUE'];
        }
    }
    $mas=array_unique($mas);
    $arResult['BRANDS'][$section['ID']]=$mas;

}

$arSelect = Array("ID", "NAME");
$arFilter = Array("IBLOCK_ID"=>2);
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
while($arFields = $res->Fetch())
{
    foreach($arResult['BRANDS'] as $key=>&$brands){
        foreach($brands as $key2=>&$one){
            if($key2==$arFields['ID']){
                $one=$arFields['NAME'];
            }
        }
    }
}
