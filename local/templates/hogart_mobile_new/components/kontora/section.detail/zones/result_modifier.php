<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

//Элементы секции
$arResult['ELEMENTS'] = array();
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
}

//Проекты
$arResult['PROJECTS'] = array();
$arFilterProjects = array(
	"IBLOCK_ID"            => 18,
	"PROPERTY_solution_id" => $arResult['UF_PROJECTS'],
	"ACTIVE"               => "Y",
);
$resPr = CIBlockElement::GetList(array(), $arFilterProjects, false, false, array());
while ($obPr = $resPr->GetNextElement()) {
	$arFields = $obPr->GetFields();
	$arFields['PROPERTIES'] = $obPr->GetProperties();

	$res = CIBlockSection::GetByID($arFields["PROPERTIES"]['solution_id']['VALUE']);
	if ($ar_res = $res->GetNext())
		$arFields['SECTION_CODE'] = $ar_res['CODE'];

	$arResult['PROJECTS'][] = $arFields;
}