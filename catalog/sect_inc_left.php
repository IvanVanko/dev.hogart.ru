<?php

$arParams = [
    "IBLOCK_TYPE" => "catalog",
    "IBLOCK_ID" => CATALOG_IBLOCK_ID,
    "SEF_FOLDER" => "/catalog/",
    "SEF_URL_TEMPLATES" => Array("sections" => "",
        "section" => "#SECTION_CODE_PATH#/",
        "element" => "#SECTION_CODE_PATH#/#BRAND#/#ELEMENT_CODE#/",
    ),
    "TOP_DEPTH" => 1,
    "CACHE_TYPE" => "A",
    "CACHE_TIME" => "36000000",
    "CACHE_FILTER" => "Y",
    "CACHE_GROUPS" => "Y",
];

$arVariables = [];
$arDefaultUrlTemplates404 = [];
$engine = new CComponentEngine();
if (\Bitrix\Main\Loader::includeModule('iblock'))
{
    $engine->addGreedyPart("#SECTION_CODE_PATH#");
    $engine->setResolveCallback(array("CIBlockFindTools", "resolveComponentEngine"));
}

$arUrlTemplates = CComponentEngine::MakeComponentUrlTemplates($arDefaultUrlTemplates404, $arParams["SEF_URL_TEMPLATES"]);
$componentPage = $engine->guessComponentPath(
    $arParams["SEF_FOLDER"],
    $arUrlTemplates,
    $arVariables
);

if ($componentPage == "section" && $arVariables["SECTION_CODE_PATH"]) {
    $sectionID = CIBlockSection::GetList([], ['CODE' => $arVariables["SECTION_CODE_PATH"]], false, ["ID", "DEPTH_LEVEL"])->Fetch();
    if (!empty($sectionID)) {
        $arParams["SECTION_ID"] = $sectionID["ID"];
        $arParams["DEPTH_LEVEL"] = $sectionID["DEPTH_LEVEL"];
    }
}
if (empty($componentPage)) {
    $APPLICATION->IncludeComponent("bitrix:catalog.section.list", "tag_menu", $arParams);
}
