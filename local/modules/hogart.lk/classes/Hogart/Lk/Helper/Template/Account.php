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
    public static function isAuthorized()
    {
        global $USER;
        return !empty(AccountTable::getAccountByUserID($USER->GetID()));
    }
}