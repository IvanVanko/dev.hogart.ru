<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Seminars with an open date");
$APPLICATION->SetTitle("Seminars with an open date");

$arFilter['PROPERTY_sem_start_date'] = false;

if (!empty($_REQUEST["brand"]))
    foreach ($_REQUEST["brand"] as $brand)
        $arFilter["PROPERTY_brand"][] = $brand;

if (!empty($_REQUEST["direction"])) {
    foreach ($_REQUEST["direction"] as $direction) {
        if (!empty($_REQUEST['section_' . $direction])) {
            $arFilterSections = array(
                'IBLOCK_ID' => 1,
                '>=LEFT_BORDER' => $_REQUEST['section_' . $_REQUEST['section_' . $direction] . '_left'],
                '<=RIGHT_BORDER' => $_REQUEST['section_' . $_REQUEST['section_' . $direction] . '_right'],
            );
        } else {
            $arFilterSections = array(
                'IBLOCK_ID' => 1,
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

$GLOBALS['filter'] = $arFilter;

$APPLICATION->IncludeComponent("bitrix:news.list", "seminars-list-open-date", array(
    'IBLOCK_ID'     => 39,
    "IBLOCK_TYPE"   => "training",
    "SORT_BY1"      => "time",
    "SORT_ORDER1"   => "ASC",
    "PROPERTY_CODE" => array("adress"),
    "FILTER_NAME"   => "filter",
));
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>