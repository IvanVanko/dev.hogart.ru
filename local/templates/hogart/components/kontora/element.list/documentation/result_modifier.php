<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

// echo "<PRE>";
// var_dump($_REQUEST);



$arResult['BRANDS'] = array();

$arResult['EXISTS_SECTIONS'] = $_REQUEST['section'];

$request_section = ($_REQUEST['section'][0] != '' || $_REQUEST['section'][1] != '' || $_REQUEST['section'][2] != '');
$no_request = (!$request_section && !$_REQUEST['product']);

foreach ($arResult['ITEMS'] as $key => $arItem) {
	$file = CFile::GetFileArray($arItem["PROPERTIES"]['file']["VALUE"]);
	$file["FILE_SIZE"] = round($file["FILE_SIZE"] / 1048576, 2);
	$info = new SplFileInfo($file["FILE_NAME"]);
	$file["EXTENTION"] = $info->getExtension();
	$arItem["FILE"] = $file;
    $arItem['DOWNLOAD_LINK'] = $arResult['ITEMS'][$key]['DOWNLOAD_LINK'] = BXHelper::getDownloadLink($arItem['FILE']['ID'], 'download.php', str_replace(array("/","|","\\", "?", ":", ";"), "", $arItem['NAME']), $file["EXTENTION"]);

    //fileDump(array($arItem, $file), true);

	if ($request_section) {
		$exists_flag = false;

		foreach ($arItem["PROPERTIES"]["direction"]["VALUE"] as $value) {
			if (in_array($value, $_REQUEST['section'])) {
				$exists_flag = true;
			}
		}

		if ($exists_flag) {
			if ($_REQUEST['product']) {
				$exists_name_flag = false;

				if (strpos(strtolower($arItem['NAME']), strtolower($_REQUEST['product'])) !== false) {
					$exists_name_flag = true;
				}

				if ($exists_name_flag) {
					$arResult['BRANDS'][$arItem['PROPERTY_BRAND_NAME']][$arItem['PROPERTIES']['type']['VALUE']][] = $arItem;
				} else {
					unset($arResult['ITEMS'][$key]);
				}
			} else {
				$arResult['BRANDS'][$arItem['PROPERTY_BRAND_NAME']][$arItem['PROPERTIES']['type']['VALUE']][] = $arItem;
			}
		} else {
			unset($arResult['ITEMS'][$key]);
		}
	} else {
		if ($_REQUEST['product']) {
			$exists_name_flag = false;

			if (strpos(strtolower($arItem['NAME']), strtolower($_REQUEST['product'])) !== false) {
				$exists_name_flag = true;
			}

			if ($exists_name_flag) {
				$arResult['BRANDS'][$arItem['PROPERTY_BRAND_NAME']][$arItem['PROPERTIES']['type']['VALUE']][] = $arItem;
			} else {
				unset($arResult['ITEMS'][$key]);
			}
		} else {
			$arResult['BRANDS'][$arItem['PROPERTY_BRAND_NAME']][$arItem['PROPERTIES']['type']['VALUE']][] = $arItem;
		}
	}

	if ($no_request) {
		//$arResult['BRANDS'][$arItem['PROPERTY_BRAND_NAME']][$arItem['PROPERTIES']['type']['VALUE']][] = $arItem;
	}
}

//For filter
$arResult['FILTER']['TYPES'] = array();
$arFilter = array(
	"IBLOCK_ID" => $arParams['IBLOCK_ID'], 
	"ACTIVE"    => "Y",
);
$arFilter['PROPERTY_access_level'] = ($USER->IsAuthorized()) ? array(1, 2) : 1;

//Types
$res = CIBlockElement::GetList(array(), $arFilter, array('PROPERTY_TYPE'), false, array());
while ($ob = $res->GetNextElement()) {
	$arFields = $ob->GetFields();
	$arResult['FILTER']['TYPES'][] = $arFields['PROPERTY_TYPE_VALUE'];
}

//Brands
$res = CIBlockElement::GetList(array('PROPERTY_BRAND.NAME' => 'asc'), $arFilter, array('PROPERTY_BRAND'), false, array());
while ($ob = $res->GetNextElement()) {
	$arFields = $ob->GetFields();
	$arResult['FILTER']['BRANDS'][$arFields['PROPERTY_BRAND_VALUE']] = $arFields['PROPERTY_BRAND_NAME'];
}

/* Murdoc:
 * Фильтрация документов по категориям каталога
 * Выбор значений в зависимости от того, какие есть категории в данный момент у документации
 */
$arResult["FILTER"]["DIRECTIONS"] = array();
$parents_ids  = array();
$directionsId = array();
$sections     = array();

//Murdoc: Выбираем все документации, что бы получить по ним direction
$res = CIBlockElement::GetList(
	array(), 
	array(
		"IBLOCK_ID" => 10,
		"ACTIVE"    => "Y",
	), 
	array("PROPERTY_direction"), 
	false, array()
);
while ($ob = $res->GetNextElement()) {
	$arFields = $ob->GetFields();
	if ($arFields['PROPERTY_DIRECTION_VALUE']) {
		$directionsId[] = $arFields['PROPERTY_DIRECTION_VALUE'];
	}
}

//Murdoc: Выбираем все секции, их не так много, но зато не надо будет думать про родителей
$arFilterSections = array(
	"IBLOCK_ID" => 1,
);
$rsSect = CIBlockSection::GetList(array("sort" => "asc"), $arFilterSections);
while ($arSect = $rsSect->GetNext()) {
	$sections[$arSect['ID']] = $arSect;
}

//Murdoc: Проверка на существование первого уровня
function catalog_first_level_check($arSect, $sections, &$arResult) {
	$arr = &$arResult["FILTER"]["DIRECTIONS"];
	if (!array_key_exists($arSect['ID'], $arr)) {
		$section = array(
			'ID'           => $arSect['ID'],
			'NAME'         => $arSect['NAME'],
			'LEVEL'        => $arSect['DEPTH_LEVEL'],
			'LEFT_MARGIN'  => $arSect['LEFT_MARGIN'],
			'RIGHT_MARGIN' => $arSect['RIGHT_MARGIN'],
			'SECTIONS'     => array(),
		);
		$arr[$arSect['ID']] = $section;
	}
}

//Murdoc: Проверка на существование второго уровня
function catalog_second_level_check($arSect, $sections, &$arResult) {
	catalog_first_level_check($sections[$arSect['IBLOCK_SECTION_ID']], $sections, $arResult);

	$arr = &$arResult["FILTER"]["DIRECTIONS"][$arSect['IBLOCK_SECTION_ID']]['SECTIONS'];
	if (!array_key_exists($arSect['ID'], $arr)) {
		$section = array(
			'ID'           => $arSect['ID'],
			'NAME'         => $arSect['NAME'],
			'LEVEL'        => $arSect['DEPTH_LEVEL'],
			'LEFT_MARGIN'  => $arSect['LEFT_MARGIN'],
			'RIGHT_MARGIN' => $arSect['RIGHT_MARGIN'],
			'SECTIONS'     => array(),
		);
		$arr[$arSect['ID']] = $section;
	}
}

//Murdoc: Пробегаемся по всем directions и добавляем соответствующие категории и проверяем родительские
foreach ($directionsId as $one_directions) {
	$arSect = $sections[$one_directions];
	$section = array(
		'ID'           => $arSect['ID'],
		'NAME'         => $arSect['NAME'],
		'LEVEL'        => $arSect['DEPTH_LEVEL'],
		'LEFT_MARGIN'  => $arSect['LEFT_MARGIN'],
		'RIGHT_MARGIN' => $arSect['RIGHT_MARGIN'],
		'SECTIONS'     => array(),
	);

	if ($arSect['DEPTH_LEVEL'] == 1) {
		$arr = &$arResult["FILTER"]["DIRECTIONS"];
		if (!array_key_exists($arSect['ID'], $arr)) {
			$arr[$arSect['ID']] = $section;
		}
	} elseif ($arSect['DEPTH_LEVEL'] == 2) {
		catalog_first_level_check($sections[$arSect['IBLOCK_SECTION_ID']], $sections, $arResult);

		$arr = &$arResult["FILTER"]["DIRECTIONS"][$arSect['IBLOCK_SECTION_ID']]['SECTIONS'];
		if (!array_key_exists($arSect['ID'], $arr)) {
			$arr[$arSect['ID']] = $section;
		}
	} elseif ($arSect['DEPTH_LEVEL'] == 3) {
		catalog_second_level_check($sections[$arSect['IBLOCK_SECTION_ID']], $sections, $arResult);

		$arr = &$arResult["FILTER"]["DIRECTIONS"]
				[$sections[$sections[$arSect['IBLOCK_SECTION_ID']]['IBLOCK_SECTION_ID']]['ID']]
				['SECTIONS']
				[$arSect['IBLOCK_SECTION_ID']]
				['SECTIONS'];
		if (!array_key_exists($arSect['ID'], $arr)) {
			$arr[$arSect['ID']] = $section;
		}
	}
}
ksort($arResult['BRANDS']); //нормально сделать сортировку
