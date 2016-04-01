<?

$sections_id = array();
foreach ($arResult['ITEMS'] as $arItem) {
    $sections_id[] = $arItem['IBLOCK_SECTION_ID'];
}
$sections_id = array_unique($sections_id);

$arResult["ITEMS_BY_SECTIONS"] = array();



$sections = BXHelper::getSections(array(), array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ID' => $sections_id), false, array('ID', 'CODE', 'NAME'), true, 'ID');
$arResult['SECTIONS'] = $sections['RESULT'];

foreach ($arResult["ITEMS"] as $arItem) {
    $arResult["ITEMS_BY_SECTIONS"][$arItem['IBLOCK_SECTION_ID']][] = $arItem;
}

BXHelper::addCachedKeys($this->__component, array('ITEMS_BY_SECTIONS'), $arResult);
?>