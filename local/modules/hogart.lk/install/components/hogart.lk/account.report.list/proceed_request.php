<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 18/01/2017
 * Time: 17:10
 *
 * @global $APPLICATION
 */

use Hogart\Lk\Helper\Template\Account;
use Hogart\Lk\Entity\ReportTable;
use Hogart\Lk\Helper\Template\FlashSuccess;

if (!empty(Account::getAccountId()) && !empty($_REQUEST)) {
    switch ($_REQUEST['action']) {
        case 'new-report':
            ReportTable::reportRequest(Account::getAccountId(), $_REQUEST);
            new FlashSuccess("Вы будете уведомлены по готовности отчета!");
            LocalRedirect($APPLICATION->GetCurPage(false));
            break;
    }
}