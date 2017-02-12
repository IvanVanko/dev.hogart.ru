<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 02/10/2016
 * Time: 00:38
 *
 * @var $arParams array
 */

use Bitrix\Main\Type\Date;
use Hogart\Lk\Entity\AddressTable;
use Hogart\Lk\Entity\AddressTypeTable;
use Hogart\Lk\Entity\ContactInfoTable;
use Hogart\Lk\Entity\ContactTable;
use Hogart\Lk\Entity\ContactRelationTable;
use Hogart\Lk\Entity\FlashMessagesTable;
use Hogart\Lk\Entity\OrderRTUTable;
use Hogart\Lk\Entity\OrderTable;
use Hogart\Lk\Entity\OrderRTUItemTable;
use Hogart\Lk\Entity\OrderEventTable;

use Hogart\Lk\Helper\Template\FlashSuccess;
use Hogart\Lk\Helper\Template\FlashError;
use Hogart\Lk\Helper\Template\Message;

global $DB, $APPLICATION;



if (!empty($_POST['action'])) {
    switch ($_POST['action']) {
        case 'add_rtu':
            if (empty($arResult['orders'][$_REQUEST['store']])) {
                new FlashError("Ошибка: в данный момент нет возможности создать заявку!");
                LocalRedirect("/account/orders/");
            }
            $DB->StartTransaction();
            $orderRtu = [
                'store_guid' => $_REQUEST['store'],
                'account_id' => $arParams['account']['id'],
                'delivery_type' => intval($_POST['delivery_type']),
                'plan_date' => new Date($_POST['plan_date'], 'd.m.Y'),
                'plan_time' => (string)OrderRTUTable::getDateIntevalText($_POST['plan_time']),
                'email' => (string)$_POST['email'],
                'phone' => ContactInfoTable::clearPhone($_POST['phone']),
                'is_sms_notify' => (bool)$_POST['is_sms_notify'],
                'is_email_notify' => (bool)$_POST['is_email_notify'],
                'note' => (string)$_POST['comment']
            ];

            if (!empty($_POST['new_address'])) {
                $address = json_decode($_POST['__address']);
                $address_type = AddressTypeTable::getByField('code', AddressTypeTable::TYPE_DELIVERY);
                $addressRes = AddressTable::replace([
                    'owner_id' => $arParams['account']['id'],
                    'owner_type' => AddressTable::OWNER_TYPE_ACCOUNT,
                    'type_id' => $address_type['id'],
                    'postal_code' => $address->data->postal_code,
                    'region' => $address->data->region_with_type,
                    'city' => $address->data->city_with_type,
                    'street' => $address->data->street_with_type,
                    'house' => "" . $address->data->house,
                    'building' => "" . $address->block,
                    'flat' => "" . $address->data->flat,
                    'value' => $address->unrestricted_value,
                    'fias_code' => $address->data->fias_id,
                    'kladr_code' => $address->data->kladr_id,
                    'is_active' => true
                ]);
                $orderRtu['address_guid'] = $addressRes->getData()['guid_id'];
            } elseif (!empty($_POST['delivery_address'])) {
                $orderRtu['address_guid'] = (string)$_POST['delivery_address'];
            }
            if (!empty($_POST['tk_address'])) {
                $address = json_decode($_POST['__tk_address']);
                $address_type = AddressTypeTable::getByCode(AddressTypeTable::TYPE_TK);
                $addressRes = AddressTable::replace([
                    'owner_id' => $arParams['account']['id'],
                    'owner_type' => AddressTable::OWNER_TYPE_ACCOUNT,
                    'type_id' => $address_type['id'],
                    'postal_code' => $address->data->postal_code,
                    'region' => $address->data->region_with_type,
                    'city' => $address->data->city_with_type,
                    'street' => $address->data->street_with_type,
                    'house' => "" . $address->data->house,
                    'building' => "" . $address->block,
                    'flat' => "" . $address->data->flat,
                    'value' => $address->unrestricted_value,
                    'fias_code' => $address->data->fias_id,
                    'kladr_code' => $address->data->kladr_id,
                    'is_active' => true
                ]);
                $orderRtu['tk_address'] = $addressRes->getData()['guid_id'];
            }
            if (!empty($_POST['new_contact'])) {
                $result = ContactTable::createOrUpdateByField([
                    'name' => $_POST['new_name'],
                    'last_name' => $_POST['new_last_name'],
                    'middle_name' => $_POST['new_middle_name'],
                    'is_active' => true
                ], 'hash');
                if (($contact_id = $result->getId())) {
                    ContactRelationTable::replace([
                        'contact_id' => $contact_id,
                        'owner_id' => $arParams['account']['id'],
                        'owner_type' => ContactRelationTable::OWNER_TYPE_ACCOUNT
                    ]);
                    if ($_POST['email']) {
                        $ciR = ContactInfoTable::replace([
                            'owner_id' => $contact_id,
                            'owner_type' => ContactInfoTable::OWNER_TYPE_CONTACT,
                            'info_type' => ContactInfoTable::TYPE_EMAIL,
                            'value' => $_POST['new_email'],
                            'is_active' => true
                        ]);
                    }
                    if ($_POST['phone']) {
                        foreach ($_POST['new_phone'] as $kind => $phone) {
                            if (empty($phone)) continue;
                            $ciR = ContactInfoTable::replace([
                                'owner_id' => $contact_id,
                                'owner_type' => ContactInfoTable::OWNER_TYPE_CONTACT,
                                'info_type' => ContactInfoTable::TYPE_PHONE,
                                'phone_kind' => intval($kind),
                                'value' => $phone,
                                'is_active' => true
                            ]);
                        }
                    }
                }
                $orderRtu['contact_id'] = $contact_id;
            }
            if (boolval($_POST['is_tk'])) {
                $orderRtu = array_merge($orderRtu, [
                    'is_tk' => (bool)$_POST['is_tk'],
                    'tk_name' => (string)$_POST['tk_name'],
                    'driver_name' => ContactTable::getFio($_POST, 'driver_'),
                    'driver_phone' => ContactInfoTable::clearPhone($_POST['driver_phone'])
                ]);
            }

            $orderRTUResult = OrderRTUTable::add($orderRtu);
            if ($orderRTUResult->getId()) {
                $order_rtu_id = $orderRTUResult->getId();
                $rows = json_decode($_POST['rows'], true);

                $items = array_reduce(OrderTable::getByAccount($arParams['account']['id'], null, OrderTable::STATE_NORMAL, [], [
                    '=id' => array_keys($rows)
                ]), function ($result, $order) {
                        if (!empty($order)) {
                            foreach ($order['items'] as $item_group => $items) {
                                foreach ($items as $item) {
                                    $result[$item['id']] = $item;
                                }
                            }
                        }
                        return $result;
                }, []);

                $orders = [];
                $sale_max = [];
                foreach ($items as $item_id => $item) {
                    if (empty($orders[$item['order_id']])) {
                        $orders[$item['order_id']] = OrderTable::getRowById($item['order_id']);
                    }

                    $order = $orders[$item['order_id']];

                    $result = OrderRTUItemTable::addItem([
                        'order_rtu_id' => $order_rtu_id,
                        'order_id' => $item['order_id'],
                        'item_id' => $item['item_id'],
                        'item_group' => $item['item_group'],
                        'count' => $rows[$item_id]['quantity'],
                        'price' => $item['price'],
                        'discount' => $item['discount'],
                        'discount_price' => $item['discount_price'],
                        'total' => round($item['total'] / $item['count'] * $rows[$item_id]['quantity'], 2),
                        'total_vat' => round($item['total_vat'] / $item['count'] * $rows[$item_id]['quantity'], 2)
                    ], $item_id);

                    if (!$result->getId()) {
                        $DB->Rollback();
                        new FlashError("Произошла непредвиденная ошибка");
                        LocalRedirect($APPLICATION->GetCurPage(false));
                    }

                    $sale_max[$item['order_id']] += $result->getData()['total'];

                    if ($order['sale_granted'] && $order['sale_max_money'] > 0 && $order['sale_max_money'] < $sale_max[$item['order_id']]) {
                        $DB->Rollback();
                        new FlashError("Запрет на создание заявки на отгрузку: <b><u>превышена возможная сумма</u></b>", 0);
                        LocalRedirect($APPLICATION->GetCurPage(false));
                    }
                    OrderTable::resort($item['order_id']);
                    OrderTable::update($item['order_id'], [
                        'sale_max_money' => $order['sale_max_money'] - $sale_max[$item['order_id']]
                    ]);
                }

                OrderRTUTable::putTo1c($order_rtu_id);

                $credit_contracts = [];

                foreach ($orders as $order) {

                    if ($order['c_is_credit']) {
                        $credit_contracts[] = $order['contract_id'];
                    }

                    OrderEventTable::add([
                        'entity_id' => $order_rtu_id,
                        'event' => OrderEventTable::ORDER_EVENT_ORDER_RTU_CREATE,
                        'order_id' => $order['id'],
                    ]);

                    $accounts = OrderTable::getAccountsByOrder($order['id']);

                    $message = new Message(
                        OrderTable::showName($order) . " обновлен! Получена заявка на отгрузку",
                        Message::SEVERITY_INFO
                    );
                    $message
                        ->setIcon('fa fa-file-text-o')
                        ->setUrl("/account/order/" . $order['id'])
                        ->setDelay(0)
                    ;

                    foreach ($accounts as $account) {
                        if ($account['a_id'] == $arParams['account']['id']) continue;
                        FlashMessagesTable::addNewMessage($account['a_id'], $message);
                    }
                }

                foreach ($credit_contracts as $credit_contract_id) {
                    $credit_orders = OrderTable::getByAccount(\Hogart\Lk\Helper\Template\Account::getAccountId(), null, OrderTable::STATE_NORMAL, [
                        '=contract.id' => $credit_contract_id,
                    ]);

                    foreach ($credit_orders as $credit_order) {
                        OrderTable::update($credit_order['id'], [
                            'is_actual' => false
                        ]);
                    }
                }

                $DB->Commit();

                new FlashSuccess("Создана заявка на отгрузку");
                LocalRedirect("/account/orders/");
            }
            $DB->Rollback();
            break;
    }
}
