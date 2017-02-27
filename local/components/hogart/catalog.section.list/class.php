<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;

class CCatalogSectionList extends CBitrixComponent
{
    const CACHE_DIR = '/iblock/catalog/';
    const CACHE_TIME = 36000000;
    const BRAND_LEVEL = 3;

    public function onPrepareComponentParams($arParams)
    {
        if (!Loader::includeModule('iblock')) {
            die('Error including module iblock');
        }

        if ((int) $arParams['IBLOCK_ID'] <= 0) {
            die('Error');
        }
        
        if ((int) $arParams['CACHE_TIME'] <= 0) {
            $arParams['CACHE_TIME'] = self::CACHE_TIME;
        }

        return $arParams;
    }

    public function executeComponent()
    {
        $obCache = new \CPHPCache();

        $cacheId = md5(serialize($this->arParams));

        if ($obCache->InitCache($this->arParams['CACHE_TIME'], $cacheId, self::CACHE_DIR)) {
            $this->arResult = $obCache->GetVars();
        } elseif ($obCache->StartDataCache()) {
            $brandRes = CIBlockElement::GetList([], ['IBLOCK_ID' => BRAND_IBLOCK_ID]);
            $brands = [];
            while (($brandElement = $brandRes->Fetch())) {
                $brands[$brandElement['ID']] = $brandElement;
            }

            $arFilter = [
                'ACTIVE' => 'Y',
                'IBLOCK_ID' => $this->arParams['IBLOCK_ID'],
                'GLOBAL_ACTIVE'=>'Y',
            ];

            if ((int) $this->arParams['TOP_DEPTH'] > 0) {
                $arFilter['<=DEPTH_LEVEL'] = $this->arParams['TOP_DEPTH'];
            }

            $dbSection = CIBlockSection::GetList(
                ['DEPTH_LEVEL'=>'ASC','NAME'=>'ASC'],
                $arFilter,
                true,
                [
                    'IBLOCK_ID', 'ID', 'ACTIVE', 'NAME', 'IBLOCK_SECTION_ID', 'DEPTH_LEVEL', 'SORT', 'SECTION_PAGE_URL',
                    'ELEMENT_CNT'
                ]
            );
            $sectionLinc = [];
            $arSectionList = [];
            $arSectionIdWithBrands = [];

            $sectionLinc[0] = &$arSectionList;

            while($arSection = $dbSection->GetNext()) {
                if ($arSection['DEPTH_LEVEL'] == self::BRAND_LEVEL) {
                    $arSectionIdWithBrands[$arSection['ID']] = $arSection['ID'];
                }

                $sectionLinc[intval($arSection['IBLOCK_SECTION_ID'])]['SUB_SECTION'][$arSection['ID']] = $arSection;
                $sectionLinc[$arSection['ID']] = &$sectionLinc[intval($arSection['IBLOCK_SECTION_ID'])]['SUB_SECTION'][$arSection['ID']];
            }
            unset($sectionLinc);

            if (!empty($arSectionIdWithBrands)) {
                $sectionBrandRes = CIBlockElement::GetList(
                    ['PROPERTY_brand.NAME' => 'ASC'],
                    ['IBLOCK_ID' => CATALOG_IBLOCK_ID, 'SECTION_ID' => $arSectionIdWithBrands],
                    false,
                    false,
                    ['IBLOCK_SECTION_ID', 'PROPERTY_BRAND']
                );

                while ($sectionBrand = $sectionBrandRes->Fetch()) {
                    $this->arResult['SECTION_BRANDS'][$sectionBrand['IBLOCK_SECTION_ID']][$sectionBrand['PROPERTY_BRAND_VALUE']] = $brands[$sectionBrand['PROPERTY_BRAND_VALUE']];
                }
            }

            $this->arResult['SECTION_SORT'] = $arSectionList['SUB_SECTION'];

            if (!empty($this->arResult)) {
                global $CACHE_MANAGER;

                $CACHE_MANAGER->StartTagCache(self::CACHE_DIR);
                $CACHE_MANAGER->RegisterTag('iblock_id_' . $this->arParams['IBLOCK_ID']);
                $CACHE_MANAGER->RegisterTag('iblock_id_new');
                $CACHE_MANAGER->EndTagCache();

                $obCache->EndDataCache($this->arResult);
            } else {
                $obCache->AbortDataCache();
            }
        }

        $this->includeComponentTemplate();
    }
}