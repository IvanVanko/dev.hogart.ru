<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 15/08/16
 * Time: 15:17
 * @global CMain $APPLICATION
 */
define("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

use Hogart\Lk\Entity\AccountTable;
use Hogart\Lk\Entity\CartItemTable;
use Hogart\Lk\Entity\OrderTable;

global $USER, $CACHE_MANAGER;
$account = \Hogart\Lk\Helper\Template\Account::getAccount();
$count = CartItemTable::getAccountCartCount($account['id']);
$orders = OrderTable::getByAccountCount($account['id'], OrderTable::STATE_NORMAL);
if ($count) {
    LocalRedirect($APPLICATION->GetCurPage(false) . "cart/");
} elseif ($orders) {
    LocalRedirect($APPLICATION->GetCurPage(false) . "orders/");
} else {
    LocalRedirect($APPLICATION->GetCurPage(false) . "settings/");
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); 
