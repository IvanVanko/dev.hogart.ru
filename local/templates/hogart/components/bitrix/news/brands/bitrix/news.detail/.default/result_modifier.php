<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$nav = array();
$arSelect = array("ID", "NAME", 'DETAIL_PAGE_URL');
$arFilter = array("IBLOCK_ID" => $arParams['IBLOCK_ID'], "ACTIVE" => "Y", "CHECK_PERMISSIONS" => "Y");
$res = CIBlockElement::GetList($arParams['ORDER'], $arFilter, false, array("nElementID" => $arResult["ID"],
                                                                           'nPageSize' => 1), $arSelect);
while($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields();
    $nav[] = $arFields;
}

if($nav[0]['ID'] != $arResult["ID"]) {
    $arResult['PREV'] = $nav[0]['DETAIL_PAGE_URL'];
}

if($nav[count($nav) - 1]['ID'] != $arResult["ID"]) {
    $arResult['NEXT'] = $nav[count($nav) - 1]['DETAIL_PAGE_URL'];
}

//Продукция
$arResult['PRODUCTS'] = array();
//Получаем ID разделов, в которых есть элементы с брендами
$arSectionsId = array();
$arFilter = array(
    "IBLOCK_ID" => CATALOG_IBLOCK_ID,
    "PROPERTY_BRAND" => $arResult["ID"],
    "ACTIVE" => "Y"
);
$res = CIBlockElement::GetList(array(), $arFilter, array('IBLOCK_SECTION_ID'));
while($next = $res->GetNext()) {
    $arResult['PRODUCT_GROUPS'][$next['IBLOCK_SECTION_ID']] = $next;
}
$arSectionsIds = array();
foreach($arResult['PRODUCT_GROUPS'] as $arGroup) {
    $arSectionsIds[] = $arGroup['IBLOCK_SECTION_ID'];
}

$res = CIBlockSection::GetList(array('SORT' => 'ASC'), array('IBLOCK_ID' => CATALOG_IBLOCK_ID,
                                                             'ID' => $arSectionsIds), false, array('ID',
                                                                                                   'NAME',
                                                                                                   'IBLOCK_SECTION_ID',
                                                                                                   'SECTION_PAGE_URL',
                                                                                                   'CODE',
                                                                                                   'PICTURE'));

$arParentSectionIds = $arParentSectionNames = array();
while($next = $res->GetNext()) {
    $next['SECTION_PAGE_URL'] = HogartHelpers::rebuildBrandSectionHref($arParams["SEF_FOLDER"], $next["SECTION_PAGE_URL"], $arResult["CODE"]);
    $arResult['PRODUCT_SECTION_GROUPS'][$next['IBLOCK_SECTION_ID']][] = $next;
    $arParentSectionIds[] = $next['IBLOCK_SECTION_ID'];
}

$res = CIBlockSection::GetList(array('SORT' => 'ASC'), array('IBLOCK_ID' => CATALOG_IBLOCK_ID,
                                                             'ID' => $arParentSectionIds), false, array('ID',
                                                                                                        'NAME',
                                                                                                        'IBLOCK_SECTION_ID',
                                                                                                        'SECTION_PAGE_URL',
                                                                                                        'CODE',
                                                                                                        'PICTURE'));
while($next = $res->GetNext()) {
    $next['SECTION_PAGE_URL'] = HogartHelpers::rebuildBrandSectionHref($arParams["SEF_FOLDER"], $next["SECTION_PAGE_URL"], $arResult["CODE"]);
    $arResult['PARENT_SECTIONS'][] = $next;
}


BXHelper::addCachedKeys($this->__component, array('PRODUCT_GROUPS',
                                                  'PRODUCT_SECTION_GROUPS',
                                                  'PARENT_SECTIONS'), $arResult);

if (!preg_match("%^http[s]*%", $arResult['PROPERTIES']['site']['VALUE'])) {
    $arResult['PROPERTIES']['site']['VALUE'] = "http://" . $arResult['PROPERTIES']['site']['VALUE'];
}