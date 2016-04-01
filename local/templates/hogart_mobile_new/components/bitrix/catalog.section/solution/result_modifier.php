<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

//Элементы секции
$arResult['ELEMENTS'] = array();
$elementsID = array();
$arFilter = array(
	"IBLOCK_ID"  => $arResult['IBLOCK_ID'],
	"SECTION_ID" => $arResult['ID'],
	"ACTIVE"     => "Y",
);
$res = CIBlockElement::GetList(array(), $arFilter, false, false, array());
while ($ob = $res->GetNextElement()) {
	$arFields = $ob->GetFields();
	$arFields['PROPERTIES'] = $ob->GetProperties();
	$arResult['ELEMENTS'][] = $arFields;
	$elementsID[] = $arFields['ID'];
}


//Проекты
$arResult['PROJECTS'] = array();
$arFilterProjects = array(
	"IBLOCK_ID"            => 18,
	"PROPERTY_solution_id" => $arResult['ID'],
	"ACTIVE"               => "Y",
);
$resPr = CIBlockElement::GetList(array("SORT" => "ASC", "NAME" => "ASC", "ID" => "ASC"), $arFilterProjects, false, false, array());
while ($obPr = $resPr->GetNextElement()) {
	$arFields = $obPr->GetFields();
	$arResult['PROJECTS'][] = $arFields;
}

//Зоны
$arResult['ZONES'] = array();
$arFilter = array(
	"IBLOCK_ID"   => 17,
	"ACTIVE"      => "Y",
	'UF_PROJECTS' => $arResult['ID'],
);
$rsSect = CIBlockSection::GetList(array('sort' => 'asc'), $arFilter);
while ($arSect = $rsSect->GetNext()) {
	$arResult['ZONES'][] = $arSect;
}
