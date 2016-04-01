<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$nav = array();
$arSelect = array("ID", "NAME", 'DETAIL_PAGE_URL');
$arFilter = array("IBLOCK_ID" => $arParams['IBLOCK_ID'], "ACTIVE" => "Y");
$res = CIBlockElement::GetList($arParams['ORDER'], $arFilter, false, array("nElementID" => $arResult["ID"], 'nPageSize' => 1), $arSelect);
while($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields();
    $nav[] = $arFields;
}

if ($nav[0]['ID'] != $arResult["ID"])
    $arResult['PREV'] = $nav[0]['DETAIL_PAGE_URL'];

if ($nav[count($nav)-1]['ID'] != $arResult["ID"])
    $arResult['NEXT'] = $nav[count($nav)-1]['DETAIL_PAGE_URL'];

//Продукция
$arResult['PRODUCTS'] = array();
//Получаем ID разделов, в которых есть элементы с брендами
$arSectionsId = array();
$arFilter = array(
    "IBLOCK_ID"      => 1,
    "PROPERTY_BRAND" => $arResult["ID"],
    "ACTIVE" => "Y"
);
$res = CIBlockElement::GetList(array(), $arFilter, array('IBLOCK_SECTION_ID'));
while($next = $res->GetNext()) {
    $arResult['PRODUCT_GROUPS'][$next['IBLOCK_SECTION_ID']] = $next;
}
$arSectionsIds = array();
foreach ($arResult['PRODUCT_GROUPS'] as $arGroup) {
    $arSectionsIds[] = $arGroup['IBLOCK_SECTION_ID'];
}

$res = CIBlockSection::GetList(array('SORT' => 'ASC'), array('IBLOCK_ID' => 1, 'ID' => $arSectionsIds), false, array('ID','NAME','IBLOCK_SECTION_ID','CODE','PICTURE'));

$arParentSectionIds = array();
while ($next = $res->GetNext()) {
    $next['SECTION_PAGE_URL'] = HogartHelpers::rebuildBrandSectionHref($arParams["SEF_FOLDER"], $arResult["CODE"], $next["CODE"]);
    $arResult['PRODUCT_SECTION_GROUPS'][$next['IBLOCK_SECTION_ID']][] = $next;
    $arParentSectionIds[] = $next['IBLOCK_SECTION_ID'];
}

$res = CIBlockSection::GetList(array('SORT' => 'ASC'), array('IBLOCK_ID' => 1, 'ID' => $arParentSectionIds), false, array('ID','NAME','IBLOCK_SECTION_ID','CODE','PICTURE'));
while ($next = $res->GetNext()) {
    $next['SECTION_PAGE_URL'] = HogartHelpers::rebuildBrandSectionHref($arParams["SEF_FOLDER"], $arResult["CODE"], $next["CODE"]);
    $arResult['PARENT_SECTIONS'][] = $next;
}



BXHelper::addCachedKeys($this->__component, array('PRODUCT_GROUPS', 'PRODUCT_SECTION_GROUPS', 'PARENT_SECTIONS'), $arResult);
/*$arSections = array();
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
        $arSect['SECTION_PAGE_URL'] = HogartHelpers::rebuildBrandSectionHref($arParams["SEF_FOLDER"], $arResult["CODE"], $arSect["CODE"]);
        $arFilter = Array("IBLOCK_ID"=>1, "PROPERTY_brand"=>$arResult["ID"]);
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