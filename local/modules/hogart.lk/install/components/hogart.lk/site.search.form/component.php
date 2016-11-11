<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 11/11/2016
 * Time: 19:50
 *
 * @var $this CBitrixComponent
 * @var $arParams array
 *
 * @global $USER CUser
 * @global CMain $APPLICATION
 */

if (!CModule::IncludeModule("hogart.lk")) {
    $this->abortResultCache();
    ShowError("Не установлен модуль \"Модуль личного кабинета компании Хогарт\"");
    return;
}

use Hogart\Lk\Search\MainSearchSuggest;

if (!$this->initComponentTemplate())
    return;

global $USER, $CACHE_MANAGER;

//variables from component
if(!isset($arParams["PAGE"]) || strlen($arParams["PAGE"])<=0)
    $arParams["PAGE"] = "#SITE_DIR#search/index.php";

$arResult["FORM_ACTION"] = htmlspecialcharsbx(str_replace("#SITE_DIR#", SITE_DIR, $arParams["PAGE"]));
$arResult["AJAX_PARAMS"] = \CAjax::GetComponentID($this->getName(), $this->getTemplateName(), md5(serialize($arResult["FORM_ACTION"])));

if ($arResult["AJAX_PARAMS"] === $_POST["ajaxKey"]) {
    $APPLICATION->RestartBuffer();
    header("Content-Type: application/json");
    echo json_encode(MainSearchSuggest::getInstance()->search($_REQUEST['q'], SITE_ID));
    exit;
}

$this->includeComponentTemplate();