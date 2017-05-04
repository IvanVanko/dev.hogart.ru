<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 12/09/16
 * Time: 16:49
 *
 * @var $this CBitrixComponent
 * @var $arParams array
 *
 * @global $USER CUser
 * @global CMain $APPLICATION
 *
 */
if (!CModule::IncludeModule("hogart.lk")) {
    $this->abortResultCache();
    ShowError("Не установлен модуль \"Модуль личного кабинета компании Хогарт\"");
    return;
}

use Hogart\Lk\Helper\Template\FlashError;
use Hogart\Lk\Entity\AccountTable;
use Hogart\Lk\Entity\CartItemTable;
use Hogart\Lk\Helper\Template\Ajax;
use Hogart\Lk\Helper\Template\Cart;
use Hogart\Lk\Helper\Template\FlashSuccess;

if (!$this->initComponentTemplate())
    return;

global $USER, $CACHE_MANAGER;
$account = \Hogart\Lk\Helper\Template\Account::getAccount();

if ($account['id']) {
    $this->includeComponentTemplate();
}