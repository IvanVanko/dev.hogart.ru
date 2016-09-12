<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 23/08/16
 * Time: 17:21
 */

namespace Hogart\Lk;


use Hogart\Lk\Entity\AccountTable;

class Events
{
    public static function OnAfterUserLogin(&$arParams)
    {
        if (!empty($arParams['USER_ID'])) {
            $account = AccountTable::getAccountByUserID($arParams['USER_ID']);
            if (!empty($account['id'])) {
                $_SESSION["SESS_AUTH"]["ACCOUNT_ID"] = $account['id'];
            }
        }
    }
}