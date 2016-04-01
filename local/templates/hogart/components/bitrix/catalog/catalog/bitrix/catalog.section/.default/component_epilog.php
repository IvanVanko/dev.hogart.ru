<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $templateData */
/** @var @global CMain $APPLICATION */
use Bitrix\Main\Loader;
global $APPLICATION;

CModule::IncludeModule("iblock");
$res = CIBlockSection::GetByID($arResult["IBLOCK_SECTION_ID"]);
if ($ar_res = $res->GetNext())
	$parentSection = $ar_res;

//Direction
$res = CIBlockSection::GetByID($parentSection["IBLOCK_SECTION_ID"]);
if ($ar_res = $res->GetNext())
	$direction = $ar_res;

if (!empty($direction["NAME"]))
	$APPLICATION->AddChainItem($direction["NAME"], $direction["SECTION_PAGE_URL"]);

$APPLICATION->AddChainItem($parentSection["NAME"], $parentSection["SECTION_PAGE_URL"]);
$APPLICATION->AddChainItem($arResult["NAME"], "");