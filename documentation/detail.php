<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php"); ?>
<?
$brand = CIBlockElement::GetList([], [
    "IBLOCK_ID" => 2,
    "CODE" => $_REQUEST["ELEMENT_CODE"]
])->Fetch();

$APPLICATION->SetTitle("Техническая документация " . $brand["NAME"]);
$APPLICATION->AddChainItem($APPLICATION->GetTitle());
?>
<?
$arFilter = array(
    "PROPERTY_BRAND.CODE" => $_REQUEST["ELEMENT_CODE"], 
    "PROPERTY_BRAND.ACTIVE" => "Y"
);

if (isset($_REQUEST['types']) && !empty($_REQUEST['types']))
    $arFilter['PROPERTY_TYPE_VALUE'] = $_REQUEST['types'];


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

$APPLICATION->IncludeComponent("kontora:element.list", "documentation", array(
    'IBLOCK_ID' => '10',
    'ORDER' => array('PROPERTYSORT_type' => 'ASC', 'PROPERTY_type' => 'ASC', 'NAME' => 'ASC'),
    'SELECT'    => array('NAME', 'PROPERTY_brand.NAME', 'IBLOCK_ID', 'ID'),
    'PROPS'     => 'Y',
    'FILTER'    => $arFilter,
    'BRAND_ID'  => $brand["ID"],
    'BRAND_NAME' => $brand["NAME"]
));?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>