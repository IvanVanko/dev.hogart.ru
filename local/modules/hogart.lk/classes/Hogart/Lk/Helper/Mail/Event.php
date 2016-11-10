<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 28/10/2016
 * Time: 16:41
 */

namespace Hogart\Lk\Helper\Mail;


use Hogart\Lk\Entity\AccountTable;
use Hogart\Lk\Entity\CompanyTable;
use Hogart\Lk\Entity\ContactInfoTable;
use Hogart\Lk\Entity\ContractTable;
use Hogart\Lk\Helper\Template\FlashError;

class Event
{
    const COMPANY_DOC_REQUEST = "COMPANY_DOC_REQUEST";
    const HOGART_FEEDBACK = "HOGART_FEEDBACK";


    protected static function _getRequiredFields($account, &$arFields)
    {
        if (!empty($account['main_manager_id'])) {
            $manager = implode(' ', [$account['manager_last_name'], $account['manager_name'], $account['manager_middle_name']]);
            $contact = implode(' ', [$account['c_last_name'], $account['c_name'], $account['c_middle_name']]);

            $info = array_reduce(ContactInfoTable::getList([
                'filter' => [
                    '=staff.id' => $account['main_manager_id']
                ]
            ])->fetchAll(), function ($result, $item) { $result[$item['info_type']][] = $item; return $result; }, []);

            if (!empty($info[ContactInfoTable::TYPE_EMAIL])) {
                $arFields['MANAGER_EMAIL'] = reset($info[ContactInfoTable::TYPE_EMAIL])['value'];
            }

            if (empty($arFields['MANAGER_EMAIL'])) {
                return false;
            }

            $arFields['MANAGER'] = $manager;
            $arFields['ACCOUNT_NAME'] = $contact;

            return true;
        }
    }

    protected static function Send($eventName, $SITE_ID, $arFields)
    {
        $event = new \CEvent();
        $arParams = [
            "FIELDS" => &$arFields,
            "SITE_ID" => &$SITE_ID,
            "EVENT_NAME" => &$eventName,
        ];
        foreach (GetModuleEvents("hogart.lk", "OnSendEvent", true) as $arEvent)
            ExecuteModuleEventEx($arEvent, array(&$arParams));

        return $event->SendImmediate($eventName, $SITE_ID, $arFields);
    }

    public static function CompanyDocRequest($contract_id)
    {
        global $USER;

        $SITE_ID = SITE_ID;
        $eventName = self::COMPANY_DOC_REQUEST;

        $account = AccountTable::getAccountByUserID($USER->GetID());
        if (!ContractTable::isAccountContract($account['id'], $contract_id)) {
            new FlashError("У Вас нет доступа к договору");
            return false;
        }
        $contract = ContractTable::getRowById($contract_id);
        $company = CompanyTable::getRowById($contract['company_id']);

        $arFields = [
            "COMPANY_NAME" => CompanyTable::showFullName($company),
            "CONTRACT_NAME" => ContractTable::showName($contract),
        ];
        if (self::_getRequiredFields($account, $arFields)) {
            return self::Send($eventName, $SITE_ID, $arFields);
        }
    }

    public static function Feedback($subject, $message)
    {
        global $USER;

        $SITE_ID = SITE_ID;
        $eventName = self::HOGART_FEEDBACK;

        $account = AccountTable::getAccountByUserID($USER->GetID());
        $arFields = [];
        if (self::_getRequiredFields($account, $arFields)) {
            $arFields["SUBJECT"] = $subject;
            $arFields["MESSAGE"] = $message;
            return self::Send($eventName, $SITE_ID, $arFields);
        }
    }
}