<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 13/09/16
 * Time: 17:49
 *
 * @var $this CBitrixComponent
 * @var $arParams array
 *
 * @global $USER CUser
 * @global CMain $APPLICATION
 */
if (!CModule::IncludeModule("hogart.lk")) {
    $this->abortResultCache();
    ShowError("Не установлен модуль \"Модуль личного кабинета компании Хогарт\"");
    return;
}

use Hogart\Lk\Helper\Template\FlashError;
use Hogart\Lk\Entity\AccountTable;
use Hogart\Lk\Entity\CartTable;
use Hogart\Lk\Entity\ContractTable;
use Hogart\Lk\Entity\AccountStoreRelationTable;

if (!$this->initComponentTemplate())
    return;

global $USER, $CACHE_MANAGER;
$account = AccountTable::getAccountByUserID($USER->GetID());
$arParams['account'] = $account;

if ($account['id']) {

    include (__DIR__ . "/proceed_request.php");

    $arParams['step'] = (int)$_REQUEST['step'] ? : 1;

    $arResult['contracts'] = ContractTable::getByAccountId($account['id']);
    $arResult['stores'] = AccountStoreRelationTable::getByAccountId($account['id']);
    $arResult['carts'] = CartTable::getAccountCartList($account['id']);
    $arResult['item_groups'] = array_reduce(CartTable::getAllItemGroupsByAccount($account['id']), function ($result, $item) {
        if (!empty($item['item_group'])) {
            $result[] = $item['item_group'];
        }
        return $result;
    }, []);
    sort($arResult['item_groups']);

    $counter = 0;
    foreach ($arResult['carts'] as $cart) {
        $counter += $cart['items_count'];
    }

    if ($counter == 0) {
        $arParams['isEmpty'] = true;
//        \Bitrix\Main\EventManager::getInstance()->addEventHandler('main', 'OnEndBufferContent', function (&$content) {
//            if ((!defined('ADMIN_SECTION') || !ADMIN_SECTION) && !preg_match('%application/json%', $_SERVER['HTTP_ACCEPT'])) {
//                $content .= '<script language="JavaScript" type="text/javascript">';
//                $content .=<<<JS
//document.location = '/account/orders/';
//JS;
//
//                $content .= '</script>';
//            }
//        });
    }

    $measuresList = [];
    foreach ($arResult['carts'] as $cart) {
        $measuresList = array_merge($measuresList, $cart['measures']);
    }
    $measuresRes = CCatalogMeasure::getList(
        array(),
        array('@ID' => array_unique($measuresList)),
        false,
        false,
        array('ID', 'SYMBOL_RUS')
    );
    while ($measure = $measuresRes->GetNext()) {
        $arResult['measures'][$measure['ID']] = $measure['SYMBOL_RUS'];
    }

    $this->includeComponentTemplate();
} else {
    new FlashError("У Вас нет доступа в данный раздел");
    LocalRedirect("/");
}
