<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
/** @var array $templateData */
/** @var @global CMain $APPLICATION */

global $APPLICATION;

CModule::IncludeModule("iblock");
$res = CIBlockSection::GetByID($arResult["IBLOCK_SECTION_ID"]);

if($ar_res = $res->GetNext()) {
    $parentSection = $ar_res;
}

if($parentSection) {
    //Direction
    $res = CIBlockSection::GetByID($parentSection["IBLOCK_SECTION_ID"]);
    if($ar_res = $res->GetNext()) {
        $direction = $ar_res;
    }

    if(!empty($direction["NAME"])) {
        $APPLICATION->AddChainItem($direction["NAME"], $direction["SECTION_PAGE_URL"]);
    }

    $APPLICATION->AddChainItem($parentSection["NAME"], $parentSection["SECTION_PAGE_URL"]);
    $APPLICATION->AddChainItem($arResult["NAME"], "");
}

$this->__template->SetViewTarget("catalog_tab_cnt");
echo $arResult['ELEMENTS_COUNT'];
$this->__template->EndViewTarget();