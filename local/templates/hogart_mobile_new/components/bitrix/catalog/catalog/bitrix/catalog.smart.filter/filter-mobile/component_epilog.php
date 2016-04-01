<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $templateData */
/** @var @global CMain $APPLICATION */
global $APPLICATION;
CJSCore::Init(array("fx"));

if (isset($templateData['TEMPLATE_THEME']))
{
	$APPLICATION->SetAdditionalCSS($templateData['TEMPLATE_THEME']);
}

$arFilterStores = $_REQUEST['arrFilter_stores'];
$stores = array();
foreach ($arFilterStores as $arFilterStoreGroup) {
    $stores = array_merge(explode(",",$arFilterStoreGroup),$stores);
}

//слава Аллаху что в 15.0.2 сделали возможность фильтрации по складам.

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

CStorage::setVar($arCustomFilter,'CUSTOM_SECTION_FILTER');
CStorage::setVar($active_stores_filtered,'ACTIVE_STORES_FILTERED');
?>