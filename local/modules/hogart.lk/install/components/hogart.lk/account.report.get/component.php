<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 19/01/2017
 * Time: 01:18
 *
 * @global $APPLICATION
 */

if (!CModule::IncludeModule("hogart.lk")) {
    $this->abortResultCache();
    ShowError("Не установлен модуль \"Модуль личного кабинета компании Хогарт\"");
    return;
}

define("NO_SPECIAL_CHARS_CHAIN", true);

use Hogart\Lk\Entity\ReportTable;
use Hogart\Lk\Helper\Template\FlashError;
use Hogart\Lk\Helper\Template\Account;


global $USER, $CACHE_MANAGER;

if (Account::isAuthorized()) {

    if (empty($_REQUEST['report'])) {
        new FlashError("Нет доступного файла");
        LocalRedirect('/account/reports/');
        return;
    }

    $file = ReportTable::getByField("guid_id", $_REQUEST['report']);

    if ($file['account_id'] != Account::getAccountId() || empty($file['path'])) {
        new FlashError("Нет доступного файла");
        LocalRedirect('/account/reports/');
        return;
    }

    $filename = ReportTable::getFilename($_REQUEST['report']);

    $APPLICATION->RestartBuffer();
    if (ob_get_level()) ob_end_clean();
    header("Content-type:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    header("Content-Disposition:attachment;filename='{$filename}.xlsx'");
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file['path']));
    readfile($file['path']);
    exit;

} else {
    new FlashError("У Вас нет доступа в данный раздел");
    LocalRedirect("/");
}
