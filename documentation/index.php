<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Поиск документации");
$APPLICATION->SetTitle("Документация");

$arFilter = array();

if (isset($_REQUEST['types']) && !empty($_REQUEST['types']))
    $arFilter['PROPERTY_TYPE_VALUE'] = $_REQUEST['types'];

if (isset($_REQUEST['brands']) && !empty($_REQUEST['brands']))
    $arFilter['PROPERTY_BRAND'] = $_REQUEST['brands'];
    $arFilter['PROPERTY_BRAND.ACTIVE'] = "Y";

if (!empty($_REQUEST["direction"])) {
    foreach ($_REQUEST["direction"] as $direction) {
        if (!empty($_REQUEST['section_' . $direction])) {
            $arFilterSections = array(
                'IBLOCK_ID' => 1,
                'ACTIVE' => 'Y',
                '>=LEFT_BORDER' => $_REQUEST['section_' . $_REQUEST['section_' . $direction] . '_left'],
                '<=RIGHT_BORDER' => $_REQUEST['section_' . $_REQUEST['section_' . $direction] . '_right'],
            );
        } else {
            $arFilterSections = array(
                'IBLOCK_ID' => 1,
                'ACTIVE' => 'Y',
                '>=LEFT_BORDER' => $_REQUEST['direction_' . $direction . '_left'],
                '<=RIGHT_BORDER' => $_REQUEST['direction_' . $direction . '_right'],
            );
        }
        $rsSect = CIBlockSection::GetList(array('left_margin' => 'asc'), $arFilterSections);
        while ($arSect = $rsSect->GetNext()) {
            $arFilter["PROPERTY_direction"][] = $arSect["ID"];
        }
    }
}

if (isset($_REQUEST['product']) && !empty($_REQUEST['product'])) {
	$arFilterProducts = array(
		"IBLOCK_ID" => 1, 
		"ACTIVE"    => "Y",
		array(
	        "LOGIC" => "OR",
	        array("%NAME"        => $_REQUEST['product']),
	        array("PROPERTY_sku" => $_REQUEST['product']),
	    ),
	);
	$res = CIBlockElement::GetList(array(), $arFilterProducts, array('PROPERTY_docs'), false, array());
	while ($ob = $res->GetNextElement()) {
		$arFields = $ob->GetFields();
		$arFilter['ID'][] = $arFields['PROPERTY_DOCS_VALUE'];
	}
}


if (!$USER->IsAuthorized()) {
    $arFilter['PROPERTY_access_level'] = 1;
}

#DebugMessage($arFilter);
$APPLICATION->IncludeComponent("kontora:element.list", "documentation", array(
    'IBLOCK_ID' => '10',
    'ORDER' => array('NAME' => 'ASC'),
    'SELECT'    => array('NAME', 'PROPERTY_brand.NAME', 'IBLOCK_ID', 'ID'),
    'PROPS'     => 'Y',
    'FILTER'    => $arFilter,
));?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>