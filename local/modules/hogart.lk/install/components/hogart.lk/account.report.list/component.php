<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 17/01/2017
 * Time: 15:22
 */
if (!CModule::IncludeModule("hogart.lk")) {
    $this->abortResultCache();
    ShowError("Не установлен модуль \"Модуль личного кабинета компании Хогарт\"");
    return;
}

use Bitrix\Main\Localization\Loc;
use Hogart\Lk\Helper\Template\Account;
use Hogart\Lk\Helper\Template\FlashError;
use Hogart\Lk\Entity\AccountCompanyRelationTable;
use Hogart\Lk\Entity\ReportTable;

if (!$this->initComponentTemplate())
    return;

Loc::loadMessages(__FILE__);

global $USER, $CACHE_MANAGER;


if (Account::isAuthorized()) {

    include (__DIR__ . "/proceed_request.php");
    include (__DIR__ . "/proceed_filter.php");

    $arResult['reports'] = ReportTable::getByAccountId(Account::getAccountId(), $arParams['filter'] ? : [], 5);
    $arResult['companies'] = AccountCompanyRelationTable::getByAccountId(Account::getAccountId());

    foreach ($arResult['companies'] as &$company) {
        if ($company['is_favorite']) {
            $company['selected'] = 'selected="selected"';
            break;
        }
    }

    $categoriesTmp = [];
    $categoriesResult = CIBlockSection::GetList(['left_margin' => 'asc'], ['SITE_ID' => SITE_ID, 'IBLOCK_ID' => CATALOG_IBLOCK_ID, 'GLOBAL_ACTIVE' => 'Y'], false, ['ID', 'NAME', 'DEPTH_LEVEL', 'IBLOCK_SECTION_ID']);
    while ($category = $categoriesResult->GetNext()) {
        $path = [];
        if (!empty($categoriesTmp[$category['IBLOCK_SECTION_ID']]['PATH'])) {
            $path[] = $categoriesTmp[$category['IBLOCK_SECTION_ID']]['PATH'];
        }
        $path[] = $categoriesTmp[$category['IBLOCK_SECTION_ID']]['NAME'];

        $categoriesTmp[$category['ID']] = array_merge($category, [
            'PATH' => implode('/', $path)
        ]);
    }
    $maxLevel = max(array_column($categoriesTmp, 'DEPTH_LEVEL'));
    $arResult['categories'] = [];

    foreach ($categoriesTmp as $item) {
        if ($item['DEPTH_LEVEL'] != $maxLevel) continue;
        $arResult['categories'][] = $item;
    }

    $brandsResult = CIBlockElement::GetList(['NAME' => 'asc'], ['SITE_ID' => SITE_ID, 'IBLOCK_ID' => BRAND_IBLOCK_ID, 'ACTIVE' => 'Y'], false, false, ['ID', 'NAME']);

    while (($brand = $brandsResult->GetNext())) {
        $arResult['brands'][] = $brand;
    }

    $this->includeComponentTemplate();
} else {
    new FlashError("У Вас нет доступа в данный раздел");
    LocalRedirect("/");
}