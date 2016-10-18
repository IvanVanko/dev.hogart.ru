<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 23/08/16
 * Time: 17:21
 */

namespace Hogart\Lk;


use Hogart\Lk\Entity\AccountTable;
use Hogart\Lk\Entity\ContactInfoTable;

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

    public static function OnSendUserInfo(&$arParams)
    {
        if ($arParams['EVENT_NAME'] == 'ACCOUNT_NEW_USER') {
            $account = AccountTable::getAccountByUserID($arParams['FIELDS']['USER_ID']);
            if (!empty($account['id'])) {
                if (!empty($account['main_manager_id'])) {
                    $manager = implode(' ', [$account['manager_last_name'], $account['manager_name'], $account['manager_middle_name']]);

                    $info = array_reduce(ContactInfoTable::getList([
                        'filter' => [
                            '=staff.id' => $account['main_manager_id']
                        ]
                    ])->fetchAll(), function ($result, $item) { $result[$item['info_type']][] = $item; return $result; }, []);
                    if (!empty($info[ContactInfoTable::TYPE_PHONE])) {
                        $manager .= " тел.: ";
                        $phones = [];
                        foreach ($info[ContactInfoTable::TYPE_PHONE] as $phone) {
                            $phones[] = ContactInfoTable::formatPhone($phone['value']);
                        }
                        $manager .= implode(', ', $phones);
                    }
                    if (!empty($info[ContactInfoTable::TYPE_EMAIL])) {
                        $manager .= ", " . reset($info[ContactInfoTable::TYPE_EMAIL])['value'];
                    }
                    $arParams['FIELDS']['MANAGER'] = $manager;
                }
            }
        }

        return $arParams;
    }
}