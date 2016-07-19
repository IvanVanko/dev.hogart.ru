<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $templateData */
/** @var @global CMain $APPLICATION */
global $APPLICATION;
CJSCore::Init(array("ajax", "fx"));

if (isset($templateData['TEMPLATE_THEME']))
{
	$APPLICATION->SetAdditionalCSS($templateData['TEMPLATE_THEME']);
}

$items = [];
foreach ($this->arResult["ITEMS"] as $item) {
    $items[$item["CODE"]] = $item;
}
$this->arResult["ITEMS"] = $items;
$_CHECK = $this->convertUrlToCheck($arParams["~SMART_FILTER_PATH"]);

$arFilterStores = $_REQUEST['arrFilter_stores'] ? : array($_CHECK['arrFilter_stores[]']);
$stores = array();
foreach ($arFilterStores as $arFilterStoreGroup) {
    $stores = array_merge(explode(",",$arFilterStoreGroup),$stores);
}

$arCustomFilter = array();
if (!empty($stores)) {
    $arStoreSectionFilter = array('LOGIC' => 'OR');
    foreach ($stores as $store_id) {
        $arStoreSectionFilter[] = array(">CATALOG_STORE_AMOUNT_$store_id" => '0');
    }
    $arCustomFilter[] = $arStoreSectionFilter;
    $active_stores_filtered = "Y";
}


$arFilterWarehouse = $_REQUEST['arrFilter_warehouse'];
if (!empty($arFilterWarehouse)) {
    $arCustomFilter['PROPERTY_warehouse'] = '1';

}

if (!empty($arCustomFilter)) {
    $GLOBALS[$arParams["FILTER_NAME"]] = array_merge($GLOBALS[$arParams["FILTER_NAME"]], (array)$arCustomFilter);
}