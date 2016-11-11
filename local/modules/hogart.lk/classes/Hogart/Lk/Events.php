<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 23/08/16
 * Time: 17:21
 */

namespace Hogart\Lk;


use Bitrix\Main\Mail\Event;
use Hogart\Lk\Entity\AccountTable;
use Hogart\Lk\Entity\ContactInfoTable;
use Hogart\Lk\Exchange\RabbitMQ\Consumer;
use Hogart\Lk\Exchange\RabbitMQ\Exchange\SiteExchange;

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
            $account = AccountTable::getAccountByUserID($arParams['FIELDS']['USER_ID'], true, false);
            if (!empty($account['id'])) {
                if (!empty($account['main_manager_id'])) {
                    $manager = implode(' ', [$account['manager_last_name'], $account['manager_name'], $account['manager_middle_name']]);
                    $contact = implode(' ', [$account['c_last_name'], $account['c_name'], $account['c_middle_name']]);

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
                    $arParams['FIELDS']['ACCOUNT_NAME'] = $contact;
                }
            }
        }

        return $arParams;
    }

	public static function OnBeforeEventAdd(&$event, &$lid, &$arFields, &$message_id, &$files)
    {
        if ($event == 'USER_PASS_CHANGED') {
            $account = AccountTable::getAccountByUserID($arFields['USER_ID'], true, false);
            if (!empty($account['id'])) {
                if (!empty($account['main_manager_id'])) {
                    $manager = implode(' ', [$account['manager_last_name'], $account['manager_name'], $account['manager_middle_name']]);
                    $contact = implode(' ', [$account['c_last_name'], $account['c_name'], $account['c_middle_name']]);
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
                    $arFields['MANAGER'] = $manager;
                    $arFields['ACCOUNT_NAME'] = $contact;
                    $arFields['ACTIVE'] = $account['is_active'] ? "активен" : "заблокирован";
                }
                $event = 'ACCOUNT_CHANGED_PASSWORD';
            }
        }
    }

    public static function OnAfterIBlockElementAdd(&$arParams)
    {
        $exchange = new SiteExchange(Consumer::getInstance());
        $exchange->publish($arParams["ID"], SiteExchange::INDEX_ITEM);
    }

    public static function OnAfterIBlockElementUpdate(&$arParams)
    {
        $exchange = new SiteExchange(Consumer::getInstance());
        $exchange->publish($arParams["ID"], $arParams['ACTIVE'] == 'Y' ? SiteExchange::INDEX_ITEM : SiteExchange::INDEX_DELETE_ITEM);
    }

    public static function OnAfterIBlockElementDelete(&$arParams)
    {
        $exchange = new SiteExchange(Consumer::getInstance());
        $exchange->publish($arParams["ID"], SiteExchange::INDEX_DELETE_ITEM);
    }
}