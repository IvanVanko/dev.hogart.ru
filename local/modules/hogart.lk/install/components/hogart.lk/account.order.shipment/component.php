<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 01/10/2016
 * Time: 16:51
 *
 * @global $APPLICATION
 */

if (!CModule::IncludeModule("hogart.lk")) {
    $this->abortResultCache();
    ShowError("Не установлен модуль \"Модуль личного кабинета компании Хогарт\"");
    return;
}
if (!$this->initComponentTemplate())
    return;

use Hogart\Lk\Entity\AccountTable;
use Hogart\Lk\Entity\OrderTable;
use Hogart\Lk\Helper\Template\FlashError;
use Hogart\Lk\Entity\StoreTable;
use Hogart\Lk\Entity\AddressTable;
use Hogart\Lk\Entity\AddressTypeTable;
use Hogart\Lk\Entity\ContactRelationTable;

define("NO_SPECIAL_CHARS_CHAIN", true);

global $USER, $CACHE_MANAGER;
$account = AccountTable::getAccountByUserID($USER->GetID());
$arParams['account'] = $account;

if ($account['id']) {

    include (__DIR__ . "/proceed_request.php");

    $arResult['orders'] = OrderTable::getShipmentOrders($account['id'], (string)$_REQUEST['store']);
    $arResult['stores'] = array_reduce(StoreTable::getByXmlId(array_keys($arResult['orders'])), function ($result, $store) {
        $result[$store['XML_ID']] = $store;
        return $result;
    }, []);

    $type_id = AddressTypeTable::getByField('code', AddressTypeTable::TYPE_DELIVERY)['id'];
    $arResult['addresses'] = array_reduce(AddressTable::getByOwner($account['id'], AddressTable::OWNER_TYPE_ACCOUNT, [
        '=type_id' => $type_id
    ])[$type_id], function ($result, $address) {
        $result[$address['guid_id']] = $address;
        return $result;
    });

    $arResult['contacts'] = array_reduce(ContactRelationTable::getContactsByOwner($account['id'], ContactRelationTable::OWNER_TYPE_ACCOUNT), function ($result, $contact) {
        $result[$contact['id']] = $contact;
        return $result;
    }, []);

    $arResult['contacts'] += array_reduce([AccountTable::getContact($account['id'])], function ($result, $contact) {
        $result[$contact['id']] = $contact;
        return $result;
    }, []);

    foreach ($arResult['orders'] as $store => $orders) {
        foreach ($orders as $order) {
            $arResult['addresses'] = array_merge($arResult['addresses'] ? : [], $order['addresses']);
            $arResult['contacts'] += $order['contacts'];
        }
    }

    $arResult['addresses'] = array_reduce($arResult['addresses'], function ($result, $address) {
        if (!isset($result[$address['fias_code']])) {
            $result[$address['fias_code']] = $address;
        }
        return $result;
    }, []);

    if (empty($arResult['orders'])) {
        new FlashError("Нет позиций для отгрузки!");
        LocalRedirect('/account/orders/');
        return;
    }
    $this->includeComponentTemplate();
} else {
    new FlashError("У Вас нет доступа в данный раздел");
    LocalRedirect("/");
}
