<? if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)	die();

$branchBrand = [];

foreach ($arResult['ITEMS'] as $ITEM) {
    foreach ($ITEM['PROPERTIES']['BRANCH']['VALUE'] as $BRANCH) {
        $branchBrand[$BRANCH]['ITEMS'][] = $ITEM;
    }
}

foreach ($branchBrand as $ID => &$item) {
    $item['BRANCH'] = CIBlockSection::GetList([], ['=ID' => $ID])->Fetch();
}

$arResult['BRAND_BRANCH'] = $branchBrand;

?>

