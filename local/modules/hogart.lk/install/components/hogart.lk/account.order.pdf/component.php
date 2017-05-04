<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 10/10/2016
 * Time: 21:32
 * @global $APPLICATION
 */
if (!CModule::IncludeModule("hogart.lk")) {
    $this->abortResultCache();
    ShowError("Не установлен модуль \"Модуль личного кабинета компании Хогарт\"");
    return;
}

define("NO_SPECIAL_CHARS_CHAIN", true);

use Hogart\Lk\Entity\AccountTable;
use Hogart\Lk\Entity\OrderTable;
use Hogart\Lk\Helper\Template\FlashError;
use Hogart\Lk\Entity\PdfTable;


global $USER, $CACHE_MANAGER;
$account = \Hogart\Lk\Helper\Template\Account::getAccount();
$arParams['account'] = $account;

if ($account['id']) {

    if (!OrderTable::isHaveAccess($account['id'], intval($_REQUEST['order']))) {
        new FlashError("У вас нет доступа к заказу!");
        LocalRedirect('/account/orders/');
        return;
    }

    if (empty($_REQUEST['pdf'])) {
        new FlashError("Нет доступного файла");
        LocalRedirect('/account/orders/');
        return;
    }

    $pdf = PdfTable::getByField("guid_id", $_REQUEST['pdf']);

    if (empty($pdf['path'])) {
        new FlashError("Нет доступного файла");
        LocalRedirect('/account/orders/');
        return;
    }
    $order = OrderTable::getOrder(intval($_REQUEST['order']));
    $filename = vsprintf(PdfTable::getTitle($_REQUEST['pdf']), [$order['number']]);

    $APPLICATION->RestartBuffer();
    header("Content-type:application/pdf");
    header("Content-Disposition:attachment;filename='{$filename}.pdf'");
    readfile($pdf['path']);
    exit;

} else {
    new FlashError("У Вас нет доступа в данный раздел");
    LocalRedirect("/");
}
