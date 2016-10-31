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
use Hogart\Lk\Entity\OrderRTUTable;
use Hogart\Lk\Entity\OrderTable;
use Hogart\Lk\Entity\OrderRTUItemTable;
use Hogart\Lk\Entity\OrderEventTable;
use Hogart\Lk\Helper\Template\FlashSuccess;


if (!empty($_POST['action'])) {
    switch ($_POST['action']) {
        case 'add_rtu':
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
                foreach ($items as $item_id => $item) {
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
                    OrderTable::resort($item['order_id']);
                    $orders[] = $item['order_id'];
                }

                OrderRTUTable::putTo1c($order_rtu_id);

                foreach (array_unique($orders) as $order_id) {
                    OrderEventTable::add([
                        'entity_id' => $order_rtu_id,
                        'event' => OrderEventTable::ORDER_EVENT_ORDER_RTU_CREATE,
                        'order_id' => $order_id,
                    ]);
                }

                new FlashSuccess("Создан запрос на отгрузку");
                LocalRedirect("/account/orders/");
            }
            break;
    }
}
