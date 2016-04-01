<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
foreach ($arResult["SECTIONS"] as $k => $arSection) {
    if ($arSection["ELEMENT_CNT"] == 0) {
        unset($arResult['SECTIONS'][$k]);
    }
}