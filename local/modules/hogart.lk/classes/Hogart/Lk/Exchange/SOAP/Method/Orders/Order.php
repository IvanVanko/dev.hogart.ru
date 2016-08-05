<?php
/**
 * By: Ivan Kiselev aka shaqito[at]gmail.com
 * Via: PhpStorm.
 * At: 04.08.2016 10:01
 */

namespace Hogart\Lk\Exchange\SOAP\Method\Orders;

use Bitrix\Catalog\StoreTable;
use Hogart\Lk\Entity\OrderTable;
use Hogart\Lk\Exchange\SOAP\AbstractMethod;
use Bitrix\Main\Type\Date;
use Hogart\Lk\Entity\AccountTable;
use Hogart\Lk\Entity\CompanyTable;
use Hogart\Lk\Entity\StaffTable;
use Hogart\Lk\Entity\ContractTable;
use Hogart\Lk\Entity\HogartCompanyTable;
use Bitrix\Main\Entity\UpdateResult;
use Hogart\Lk\Exchange\SOAP\Method\MethodException;
use Hogart\Lk\Exchange\SOAP\Method\Response;
use Hogart\Lk\Exchange\SOAP\Method\ResponseObject;

/**
 * Class Order - добавление Заказа (в КИС это Заголовок Заказа)
 * @package Hogart\Lk\Exchange\SOAP\Method\Orders
 */
class Order extends AbstractMethod
{
    /**
     * @inheritDoc
     */
    function getName()
    {
        return "Order";
    }

    /**
     * @param $orders
     * @param Response $answer
     * @return int
     */
    public function updateOrders($orders, Response $answer)
    {
        $answer = new Response();

        foreach ($orders as $order) {

            $hogart_company = HogartCompanyTable::getByField('guid_id', $order->Order_ID_Hogart);
            $client_company = CompanyTable::getByField('guid_id', $order->Order_ID_Company);
            $contract = ContractTable::getByField('guid_id', $order->Order_ID_Contract);
            $stock_store = StoreTable::getList(['filter' => ['=XML_ID' => $order->Order_ID_Stock]])->fetch();
            $manager = StaffTable::getByField('guid_id', $order->Order_ID_Staff);
            $account = AccountTable::getByField('user_guid_id', $order->Order_ID_Account);
            // @todo Добавить обработку ошибок
            if (!isset($hogart_company)) {
            }
            if (!isset($client_company)) {
            }
            if (!isset($contract)) {
            }
            if (!isset($stock_store)) {
            }


            $result = OrderTable::createOrUpdateByField([
                'guid_id' => $order->Order_ID,
                'company_id' => $client_company['id'] ?: 0,
                'hogart_company_id' => $hogart_company['id'] ?: 0,
                'number' => $order->Order_Number,
                'order_date' =>  new Date((string)$order->Order_Date, 'Y-m-d'),
                'order_type' => $order->Order_Form_Oper,
                'contract_id' => $contract['id'],
                'order_status' => $order->Order_Status,
                'store_id' => $stock_store['ID'],
                'staff_id' => $manager['id'] ?: 0,
                'account_id' => $account['id'] ?: 0,
                'note' => $order->Order_Note,
                'sale_granted' => $order->Order_Sale_Granted,
                'sale_max_money' => $order->Order_Max_Monet_Sale,
                'perm_bill' => $order->Order_Perm_Bill,
                'perm_reserve' => $order->Order_Perm_Reserve,
                'currency_code' => $order->Order_ID_Money,
                'is_active' => $order->deletion_mark,
            ], 'guid_id');

            if ($result->getErrorCollection()->count()) {
                $error = $result->getErrorCollection()->current();
                $answer->addResponse(new ResponseObject($order->Order_ID, new MethodException($error->getMessage(), intval($error->getCode()))));
            } else {
                if ($result->getId()) {
                    if ($result instanceof UpdateResult) {
                        $this->client->getLogger()->notice("Обновлена запись Заказа {$result->getId()} ({$order->Order_ID})");
                    } else {
                        $this->client->getLogger()->notice("Добавлена запись Заказа {$result->getId()} ({$order->Order_ID})");
                    }
                    $answer->addResponse(new ResponseObject($order->Order_ID));
                } else {
                    $answer->addResponse(new ResponseObject($order->Order_ID, new MethodException(self::$default_errors[self::ERROR_UNDEFINED], self::ERROR_UNDEFINED)));
                }
            }
        }
        // @todo Шлем ответ или нет? перепроверить короч
        return count($answer->Response);
    }
}