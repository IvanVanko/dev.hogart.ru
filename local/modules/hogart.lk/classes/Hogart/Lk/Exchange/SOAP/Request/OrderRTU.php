<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 06/10/2016
 * Time: 18:43
 */

namespace Hogart\Lk\Exchange\SOAP\Request;


use Hogart\Lk\Entity\AccountTable;
use Hogart\Lk\Entity\AddressTable;
use Hogart\Lk\Entity\ContactTable;
use Hogart\Lk\Entity\OrderRTUTable;
use Hogart\Lk\Entity\OrderTable;
use Hogart\Lk\Exchange\SOAP\AbstractPutRequest;
use Hogart\Lk\Exchange\SOAP\LazyRequest;

class OrderRTU extends AbstractPutRequest
{
    protected $orders = [];

    /**
     * OrderRTU constructor.
     * @param array $order_rtu
     */
    public function __construct(array $order_rtu = [])
    {
        foreach ($order_rtu as $rtu) {
            $order = (object)[
                "Ord_RTU_ID" => (string)$rtu['guid_id'],
                "Ord_RTU_ID_Site" => $rtu['id'],
                "Ord_RTU_Number" => (string)$rtu['number'],
                "Ord_RTU_Date" => $rtu['rtu_date']->format('Y-m-d H:i:s'),
                "Ord_RTU_Delivery" => $rtu['delivery_type'],
                "Ord_RTU_Warehouse_ID" => $rtu['store_guid'],
                "Ord_RTU_Address" => new LazyRequest(function ($rtu) {
                    if (($address = AddressTable::getByField('guid_id', $rtu['address_guid'])))
                        return (string)$address['value'];
                }, [$rtu]),
                "Ord_RTU_PlanDate" => $rtu['plan_date']->format('Y-m-d'),
                "Ord_RTU_PlanTime" => $rtu['plan_time'],
                "Ord_RTU_ID_Contact" => new LazyRequest(function ($rtu) {
                    if (($contact = ContactTable::getRowById($rtu['contact_id'])))
                        return (string)$contact['guid_id'];
                }, [$rtu]),
                "Ord_RTU_Email" => $rtu['email'],
                "Ord_RTU_Phone_Contact" => $rtu['phone'],
                "Ord_RTU_SendSMS" => boolval($rtu['is_sms_notify']),
                "Ord_RTU_SendEmail" => boolval($rtu['is_email_notify']),
                "Ord_RTU_TK" => boolval($rtu['is_tk']),
                "Ord_RTU_TK_Name" => $rtu['tk_name'],
                "Ord_RTU_TK_Address" => new LazyRequest(function ($rtu) {
                    if (($address = AddressTable::getByField('guid_id', $rtu['tk_address'])))
                        return (string)$address['value'];
                }, [$rtu]),
                "deletion_mark" => !$rtu['is_active'],
                "Ord_RTU_ID_Account" => new LazyRequest(function ($rtu) {
                    return (string)AccountTable::getRowById($rtu['account_id'])['user_guid_id'];
                }, [$rtu]),
                "Ord_RTU_Description" => $rtu['note']
            ];
            foreach ($rtu['items'] as $item_group => $items) {
                foreach ($items as $item) {
                    $order->Ord_RTU_Items[] = (object)[
                        "RTU_Item_ID" => $rtu['guid_id'] ? implode('_', [$rtu['guid_id'], $item['guid_id']]) : '',
                        "ID_Item" => $item['XML_ID'],
                        "Count" => $item['count'],
                        "Cost" => floatval($item['price']),
                        "Discount" => floatval($item['discount']),
                        "Cost_Disc" => floatval($item['discount_price']),
                        "Summ" => floatval($item['total']),
                        "Sum_VAL" => floatval($item['total_vat']),
                        "Group" => $item_group,
                        "Ship_Date" => $item[''],
                        "RTU_ID" => $rtu['guid_id'],
                        "Order_ID" => new LazyRequest(function ($item) {
                            return (string)OrderTable::getRowById($item['order_id'])['guid_id'];
                        }, [$item]),
                    ];
                }
            }
            $this->orders[] = $order;
        }
    }

    public function getAddress($guid)
    {
        $address = AddressTable::getByField('guid_id', $guid);
        return (string)$address['value'];
    }

    protected function request()
    {
        return ['Order_RTU' => $this->orders];
    }
}
