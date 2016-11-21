<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 27/09/2016
 * Time: 03:38
 */

namespace Hogart\Lk\Exchange\SOAP\Request;


use Hogart\Lk\Entity\OrderTable;
use Hogart\Lk\Exchange\SOAP\AbstractPutRequest;
use Hogart\Lk\Exchange\SOAP\LazyRequest;

class Order extends AbstractPutRequest
{
    /**
     * @var array
     */
    private $orders;

    /**
     * Order constructor.
     * @param array $orders
     */
    public function __construct($orders = [])
    {
        foreach ($orders as $order) {
            $_order = (object)[
                'Order_ID_Hogart' => new LazyRequest(function ($order) {
                    $order = OrderTable::getOrder($order['id']);
                    return $order['hco_guid_id'];
                }, [$order]),
                'Order_ID_Company' => new LazyRequest(function ($order) {
                    $order = OrderTable::getOrder($order['id']);
                    return $order['co_guid_id'];
                }, [$order]),
                'Order_ID' => (string)$order['guid_id'],
                'Order_ID_Site' => $order['id'],
                'Order_Date' => $order['order_date']->format('Y-m-d H:i:s'),
                'Order_Number' => $order['number'],
                'Order_Form_Oper' => $order['type'],
                'Order_ID_Contract' => new LazyRequest(function ($order) {
                    $order = OrderTable::getOrder($order['id']);
                    return $order['c_guid_id'];
                }, [$order]),
                'Order_Status' => $order['status'],
                'Order_ID_Stock' => $order['store_guid'],
                'Order_ID_Account' => $order['a_user_guid_id'],
                'Order_ID_Staff' => $order['m_guid_id'],
                'Order_Note' => $order['note'],
                'Order_Sale_Granted' => $order['sale_granted'],
                'Order_Max_Monet_Sale' => $order['sale_max_money'],
                'Order_Reserve' => $order['perm_reserve'],
                'Order_ID_Money' => $order['c_currency_code'],
                'deletion_mark' => !$order['is_active']
            ];
            foreach ($order['items'] as $item_group => $items) {
                foreach ($items as $k => $item) {
                    $_order->Order_Items[] = (object)[
                        "Order_Line_Number" => (int)$item['string_number'],
                        "Order_Item_ID" => '',
                        "ID_Item" => $item['XML_ID'],
                        "Item_Article" => $item['props']['sku']['VALUE'],
                        "Item_Name" => $item['NAME'],
                        "Count" => (int)$item['count'],
                        "Group" => $item['item_group'],
                        "Cost" => round((float)$item['price'], 2),
                        "Discount" => round((float)$item['discount'], 2),
                        "Cost_Disc" => round((float)$item['discount_price'], 2),
                        "Summ" => round((float)$item['total'], 2),
                        "Sum_VAL" => round((float)$item['total_vat'], 2),
                        "Status_Item" => (int)$item['status'],
                        "Delivery_Time" => $item['delivery_time']->format('Y-m-d'),
                    ];
                }
            }
            $this->orders[] = $_order;
        }
    }

    protected function request()
    {
        return ['Order' => $this->orders];
    }
}
