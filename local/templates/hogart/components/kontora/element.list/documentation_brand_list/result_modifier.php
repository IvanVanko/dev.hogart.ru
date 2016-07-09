<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 09/07/16
 * Time: 23:58
 */
$arResult['BRANDS'] = array();
foreach ($arResult['ITEMS'] as $key => $arItem) {
    if (array_key_exists($arItem["PROPERTY_BRAND_ID"], $arResult['BRANDS'])) continue;
    $arResult['BRANDS'][$arItem["PROPERTY_BRAND_ID"]] = CIBlockElement::GetByID($arItem["PROPERTY_BRAND_ID"])->Fetch();
    $arResult['BRANDS'][$arItem["PROPERTY_BRAND_ID"]]["LINK"] = $arParams["SEF_URL"] . $arResult['BRANDS'][$arItem["PROPERTY_BRAND_ID"]]["CODE"] . "/";
}
usort($arResult['BRANDS'], function ($a, $b) {
    return $a["NAME"] > $b["NAME"];
});