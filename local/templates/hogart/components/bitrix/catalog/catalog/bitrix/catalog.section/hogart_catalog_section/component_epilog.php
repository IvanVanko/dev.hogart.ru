<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $templateData */
/** @var @global CMain $APPLICATION */
global $APPLICATION;
if (count($arResult["ITEMS"])==0)
LocalRedirect('/catalog/index.php');

CModule::IncludeModule("iblock");
$res = CIBlockSection::GetByID($arResult["IBLOCK_SECTION_ID"]);
if ($ar_res = $res->GetNext())
	$parentSection = $ar_res;

//Direction
$res = CIBlockSection::GetByID($parentSection["IBLOCK_SECTION_ID"]);
if ($ar_res = $res->GetNext())
	$direction = $ar_res;

CStorage::setVar($this->arResult['ELEMENTS'],'CURRENT_SECTION_ELEMENTS');
CStorage::setVar($this->arResult['DEPTH_LEVEL'],'SECTION_DEPTH_LEVEL');
CStorage::setVar($this->arResult['CUSTOM_META'],'SECTION_CUSTOM_META');