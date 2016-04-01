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
BXHelper::addCachedKeys($this->__component, array('SECTIONS_F'), $arResult);