<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 06/10/2016
 * Time: 18:43
 */

namespace Hogart\Lk\Exchange\SOAP\Request;


use Hogart\Lk\Entity\AddressTable;
use Hogart\Lk\Entity\ContactTable;
use Hogart\Lk\Entity\OrderRTUTable;
use Hogart\Lk\Exchange\SOAP\AbstractPutRequest;

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
            $this->orders[] = (object)[
                "Ord_RTU_ID" => (string)$rtu['guid_id'],
                "Ord_RTU_ID_Site" => $rtu['id'],
                "Ord_RTU_Number" => (string)$rtu['number'],
                "Ord_RTU_Date" => $rtu['rtu_date']->format(HOGART_DATE_TIME_FORMAT),
                "Ord_RTU_ID_Money" => '',
                "Ord_RTU_Delivery" => $rtu['delivery_type'],
                "Ord_RTU_Warehouse_ID" => $rtu['store_guid'],
                "Ord_RTU_Address" => function () use ($rtu) {
                    return (string)OrderRTUTable::getRowById($rtu['id'])['address_guid'];
                },
                "Ord_RTU_PlanDate" => $rtu['plan_date']->format(HOGART_DATE_FORMAT),
                "Ord_RTU_PlanTime" => $rtu['plan_time'],
                "Ord_RTU_ID_Contact" => function () use ($rtu) {
                    if (($contact = ContactTable::getRowById($rtu['contact_id'])))
                        return (string)$contact['guid_id'];
                },
                "Ord_RTU_Email" => $rtu['email'],
                "Ord_RTU_Phone_Contact" => $rtu['phone'],
                "Ord_RTU_SendSMS" => boolval($rtu['is_sms_notify']),
                "Ord_RTU_SendEmail" => boolval($rtu['is_email_notify']),
                "Ord_RTU_TK" => boolval($rtu['is_tk']),
                "Ord_RTU_TK_Name" => $rtu['tk_name'],
                "Ord_RTU_TK_Address" => function () use ($rtu) {
                    return (string)OrderRTUTable::getRowById($rtu['id'])['tk_address'];
                },
                "Ord_RTU_Driver" => $rtu['driver_name'],
                "Ord_RTU_Driver_Phone" => $rtu['driver_phone'],
                "deletion_mark" => !$rtu['is_active'],
                "Ord_RTU_ID_Account" => $rtu['a_user_guid_id'],
            ];
        }
    }

    protected function request()
    {
        return ['Order_RTU' => $this->orders];
    }
}
