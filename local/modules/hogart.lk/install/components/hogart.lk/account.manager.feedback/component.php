<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 25/10/2016
 * Time: 02:13
 *
 * @var CBitrixComponent $this
 */
if (!CModule::IncludeModule("hogart.lk")) {
    $this->abortResultCache();
    ShowError("Не установлен модуль \"Модуль личного кабинета компании Хогарт\"");
    return;
}
if (!$this->initComponentTemplate())
    return;

use Hogart\Lk\Entity\AccountTable;
use Hogart\Lk\Entity\ContactInfoTable;
use Hogart\Lk\Helper\Template\FlashError;
use Hogart\Lk\Helper\Mail\Event;

define("NO_SPECIAL_CHARS_CHAIN", true);

global $USER, $CACHE_MANAGER, $APPLICATION;
$account = \Hogart\Lk\Helper\Template\Account::getAccount();
$arParams['account'] = $account;


if ($account['id']) {

    if (empty($arParams['manager'])) {
        $staff_info = reset(ContactInfoTable::getByOwner($account['manager_id'], ContactInfoTable::OWNER_TYPE_STAFF, [
            '=info_type' => ContactInfoTable::TYPE_EMAIL
        ]));
        if (!empty($staff_info)) {
            $arParams['manager'] = [
                'name' => implode(" ", [$account['manager_last_name'], $account['manager_name'], $account['manager_middle_name']]),
                'id' => $account['manager_id'],
                'email' => $staff_info['value']
            ];
        }
    }

    if (
        !empty($_POST)
        && check_bitrix_sessid()
    ) {
        $subject = strip_tags(html_entity_decode($arParams["SUBJECT"] . " " . $_POST['subject']));
        $message = $_POST['message'];
        if (Event::Feedback($subject, $message)) {
            new \Hogart\Lk\Helper\Template\FlashSuccess("Сообщение менеджеру отправлено");
            LocalRedirect($APPLICATION->GetCurPage(false));
        }
    }
    $this->includeComponentTemplate();

    return $arParams;

} else {
    new FlashError("У Вас нет доступа в данный раздел");
    LocalRedirect("/");
}