<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 07/09/16
 * Time: 15:01
 */
use Bitrix\Main\Localization\Loc;

Loc::loadMessages($_SERVER['DOCUMENT_ROOT'] . "/bitrix/components/hogart.lk/account.order.list/component.php");
$aMenuLinks = [
    [
        Loc::getMessage('active'),
        "/account/orders/active/",
        [],
        ["icon" => "fa fa-cogs"]
    ],
    [
        Loc::getMessage('draft'),
        "/account/orders/draft/",
        [],
        ["icon" => "fa fa-pencil"]
    ],
    [
        Loc::getMessage('archive'),
        "/account/orders/archive/",
        [],
        ["icon" => "fa fa-archive"]
    ],
];
