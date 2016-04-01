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
//Продукция
//$arResult['PRODUCTS'] = array();
////Получаем ID разделов, в которых есть элементы с брендами
//$arSectionsId = array();
//$arFilter = array(
//	"IBLOCK_ID"      => 1,
//	"PROPERTY_BRAND" => $arResult["ID"],
//);
//
//$res = CIBlockElement::GetList(array(), $arFilter, false, false, array());
//while($ob = $res->GetNextElement()) {
//	$arFields = $ob->GetFields();
//    fileDump($arFields, true);
//	$arSectionsId[] = $arFields['IBLOCK_SECTION_ID'];
//}
//
//$arSections = array();
//$rsSect = CIBlockSection::GetList(array("left_margin" => "asc"), array('ID' => $arSectionsId));
//while ($arSect = $rsSect->GetNext()) {
//	$arSections[] = $arSect;
//}
////Получить все родительские разделы этих секций
////var_dump($arSections);
//foreach ($arSections as $arSection) {
//	$arFilter = array(
//		"IBLOCK_ID"      => 1,
//		'<=LEFT_BORDER'  => $arSection['LEFT_MARGIN'],
//		'>=RIGHT_BORDER' => $arSection['RIGHT_MARGIN'],
//		'>DEPTH_LEVEL'   => 1,
//
//	);
//	$rsSect = CIBlockSection::GetList(array("left_margin" => "asc"), $arFilter, true);
//	while ($arSect = $rsSect->GetNext()) {
//        $arFilter = Array("IBLOCK_ID"=>1, "PROPERTY_brand"=>$arResult['ID']);
//        if ($arSect['ELEMENT_CNT']>0){
//
//            $arSect['COUNT_PRODS'] = CIBlockElement::GetList(Array(), $arFilter, array(), false, array());
//        }
//
//
//		if ($arSect['DEPTH_LEVEL'] == 2)
//
//                $arResult['PRODUCTS'][$arSect['ID']] = $arSect;
//
//		elseif ($arSect['DEPTH_LEVEL'] == 3)
//            if ($arSect['ELEMENT_CNT']>0) {
//                $arResult['PRODUCTS'][$arSect['IBLOCK_SECTION_ID']]['SECTIONS'][$arSect['ID']] = $arSect;
//            }
//	}
//}