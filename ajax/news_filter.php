<? require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); 
CModule::IncludeModule("iblock");
$data = array();
$ids = array();

$arFilter = array(
	"IBLOCK_ID" => 3, 
	"ACTIVE"    => "Y"
);
$res = CIBlockElement::GetList(array("property_catalog_section.name" => "asc"), $arFilter, array("PROPERTY_catalog_section"), false, array());
while ($ob = $res->GetNextElement()) {
	$arFields = $ob->GetFields();
	$ids[] = $arFields["PROPERTY_CATALOG_SECTION_VALUE"];
}

if (!empty($_REQUEST["directionID"])) {
	$arFilter = array(
		"IBLOCK_ID"    => 1,
		">DEPTH_LEVEL" => 1,
		"SECTION_ID"   => $_REQUEST["directionID"]
	);
	$rsSect = CIBlockSection::GetList(array("left_margin" => "asc"), $arFilter);
	while ($arSect = $rsSect->GetNext()) {
		if (in_array($arSect["ID"], $ids))
			$data[$arSect["ID"]] = $arSect["NAME"];
	}
}
else {
	$rsSect = CIBlockSection::GetList(array("left_margin" => "asc"), array('ID' => $ids));
	while ($arSect = $rsSect->GetNext()) {
		$data[$arSect["ID"]] = $arSect["NAME"];
	}
}
usort($data, function($a, $b){
	return strnatcmp($a['NAME'], $b['NAME']);
});
echo json_encode($data);