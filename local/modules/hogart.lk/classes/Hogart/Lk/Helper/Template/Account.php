<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 21/11/2016
 * Time: 20:45
 */

namespace Hogart\Lk\Helper\Template;


use Hogart\Lk\Entity\AccountTable;

class Account
{
    protected static $account_id;

    public static function isAuthorized()
    {
        global $USER;
        return !empty(AccountTable::getAccountByUserID($USER->GetID()));
    }

    public static function getAccountId()
    {
        global $USER;
        if (null === self::$account_id) {
            $account = AccountTable::getAccountByUserID($USER->GetID());
            if (!empty($account)) {
                self::$account_id = $account['id'];
            }
        }

        return self::$account_id;
    }
}