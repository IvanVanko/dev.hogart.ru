<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
foreach ($arResult['SECTIONS'] as $k => $setc) {
    $arResult['SECTIONS'][$setc]['PICTURE']['SRC'] = BXHelper::getResizedPictureByName(
        $arResult['SECTIONS'][$setc]['PICTURE']['SRC'], array('width' => 600, 'height' => 400), BX_RESIZE_IMAGE_PROPORTIONAL
    );
    if (!intval($setc['ELEMENT_CNT'])) {
        unset($arResult['SECTIONS'][$k]);
        continue;
    }
    $rsParentSection = CIBlockSection::GetByID($setc['ID']);
    if ($arParentSection = $rsParentSection->GetNext()) {
        $arFilter = array(
            'IBLOCK_ID' => $arParentSection['IBLOCK_ID'],
            '>LEFT_MARGIN' => $arParentSection['LEFT_MARGIN'],
            '<RIGHT_MARGIN' => $arParentSection['RIGHT_MARGIN'],
            'DEPTH_LEVEL' => 3
        ); // выберет потомков без учета активности
//            '>DEPTH_LEVEL' => $arParentSection['DEPTH_LEVEL']); // выберет потомков без учета активности

        $rsSect = CIBlockSection::GetList(array('left_margin' => 'asc'), $arFilter);
        while ($arSect = $rsSect->GetNext()) {
            $arResult['SECTIONS_F'][] = $arSect;
        }
    }
}

if ((int) $arResult["SECTION"]["ID"] > 0) {
    $db_list = CIBlockSection::GetList(Array("SORT" => "ASC"), $arFilter = Array("IBLOCK_ID" => $arResult["SECTION"]["IBLOCK_ID"], "ID" => $arResult["SECTION"]["ID"]), true, $arSelect = Array("UF_*"));
    while ($ar_result = $db_list->GetNext()) {
        foreach ($ar_result as $key => $prop) {
            if (strpos($key, "UF_") === 0 || strpos($key, "~UF_") === 0)
                $arResult["SECTION"][$key] = $prop;
        }
    }
} else {
    foreach ($arResult["SECTIONS"] as &$arSection) {
        $db_list = CIBlockSection::GetList(Array("SORT" => "ASC"), $arFilter = Array("IBLOCK_ID" => $arSection["IBLOCK_ID"], "ID" => $arSection["ID"]), true, $arSelect = Array("UF_*"));
        while ($ar_result = $db_list->GetNext()) {
            foreach ($ar_result as $key => $prop) {
                if (strpos($key, "UF_") === 0 || strpos($key, "~UF_") === 0)
                    $arSection[$key] = $prop;
            }
        }
    }
}


/*$arFilter = array(
    "IBLOCK_ID"      => 1,
    '<=LEFT_BORDER'  => $arSection['LEFT_MARGIN'],
    '>=RIGHT_BORDER' => $arSection['RIGHT_MARGIN'],
    'DEPTH_LEVEL'   => 1,

);
$arSelect = array("ID", "NAME", 'DETAIL_PAGE_URL');
$res = CIBlockSection::GetList(array("RIGHT_BORDER" => "asc"), $arFilter, false, false, $arSelect);
while($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields();
    $arResult['SECTIONS_F'][] = $arFields;
}*/
//echo '<pre>';
//var_dump($arResult['SECTIONS_F']);
//echo '</pre>';
/*$nav = array();
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
*/
//Продукция
/*$arResult['PRODUCTS'] = array();*/
//Получаем ID разделов, в которых есть элементы с брендами
/*$arSectionsId = array();
$arFilter = array(
	"IBLOCK_ID"      => 1,
	"PROPERTY_BRAND" => $arResult["ID"],
);

$res = CIBlockElement::GetList(array(), $arFilter, false, false, array('IBLOCK_SECTION_ID','ID'));
while($ob = $res->GetNext()) {
	//$arFields = $ob->GetFields();
	$arSectionsId[] = $ob['IBLOCK_SECTION_ID'];
}

$arSections = array();
$rsSect = CIBlockSection::GetList(array("left_margin" => "asc"), array('ID' => $arSectionsId));
while ($arSect = $rsSect->GetNext()) {
    $arSections[] = $arSect;
}
//Получить все родительские разделы этих секций
//var_dump($arSections);
foreach ($arSections as $arSection) {
    $arFilter = array(
        "IBLOCK_ID"      => 1,
        '<=LEFT_BORDER'  => $arSection['LEFT_MARGIN'],
        '>=RIGHT_BORDER' => $arSection['RIGHT_MARGIN'],
        '>DEPTH_LEVEL'   => 1,

    );
    $rsSect = CIBlockSection::GetList(array("left_margin" => "asc"), $arFilter, true);
    while ($arSect = $rsSect->GetNext()) {
        $arFilter = Array("IBLOCK_ID"=>1, "PROPERTY_brand"=>$arResult['ID']);
        if ($arSect['ELEMENT_CNT']>0){

            $arSect['COUNT_PRODS'] = CIBlockElement::GetList(Array(), $arFilter, array(), false, array());
        }


        if ($arSect['DEPTH_LEVEL'] == 2)

            $arResult['PRODUCTS'][$arSect['ID']] = $arSect;

        elseif ($arSect['DEPTH_LEVEL'] == 3)
            if ($arSect['ELEMENT_CNT']>0) {
                $arResult['PRODUCTS'][$arSect['IBLOCK_SECTION_ID']]['SECTIONS'][$arSect['ID']] = $arSect;
            }
    }
}*/

BXHelper::addCachedKeys($this->__component, array('SECTIONS_F'), $arResult);