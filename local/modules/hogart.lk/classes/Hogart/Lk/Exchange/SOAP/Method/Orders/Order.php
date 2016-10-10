<?php
/**
 * By: Ivan Kiselev aka shaqito[at]gmail.com
 * Via: PhpStorm.
 * At: 04.08.2016 10:01
 */

namespace Hogart\Lk\Exchange\SOAP\Method\Orders;

use Bitrix\Main\Type\DateTime;
use Hogart\Lk\Entity\OrderItemTable;
use Hogart\Lk\Entity\OrderTable;
use Hogart\Lk\Entity\StoreTable;
use Hogart\Lk\Exchange\SOAP\AbstractMethod;
use Bitrix\Main\Type\Date;
use Hogart\Lk\Entity\AccountTable;
use Hogart\Lk\Entity\CompanyTable;
use Hogart\Lk\Entity\StaffTable;
use Hogart\Lk\Entity\ContractTable;
use Hogart\Lk\Entity\HogartCompanyTable;
use Bitrix\Main\Entity\UpdateResult;
use Hogart\Lk\Exchange\SOAP\MethodException;
use Hogart\Lk\Exchange\SOAP\Response;
use Hogart\Lk\Exchange\SOAP\ResponseObject;

/**
 * Class Order - добавление Заказа (в КИС это Заголовок Заказа)
 * @package Hogart\Lk\Exchange\SOAP\Method\Orders
 */
class Order extends AbstractMethod
{
    /**
     * {@inheritDoc}
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
        foreach ($orders as $order) {

            $hogart_company = HogartCompanyTable::getByField('guid_id', $order->Order_ID_Hogart);
            $client_company = CompanyTable::getByField('guid_id', $order->Order_ID_Company);
            $contract = ContractTable::getByField('guid_id', $order->Order_ID_Contract);
            $stock_store = StoreTable::getList(['filter' => ['=XML_ID' => $order->Order_ID_Stock]])->fetch();
            $manager = StaffTable::getByField('guid_id', $order->Order_ID_Staff);
            $account = AccountTable::getByField('user_guid_id', $order->Order_ID_Account);

            if (empty($account['id'])) {
                $answer->addResponse(new ResponseObject($order->Order_ID, new MethodException(MethodException::ERROR_NO_ACCOUNT, [$order->Order_ID_Account])));
                continue;
            }
            if (empty($hogart_company['id'])) {
                $answer->addResponse(new ResponseObject($order->Order_ID, new MethodException(MethodException::ERROR_NO_HOGART_COMPANY, [$order->Order_ID_Hogart])));
                continue;
            }
            if (empty($client_company['id'])) {
                $answer->addResponse(new ResponseObject($order->Order_ID, new MethodException(MethodException::ERROR_NO_CLIENT_COMPANY, [$order->Order_ID_Company])));
                continue;
            }
            if (empty($contract['id'])) {
                $answer->addResponse(new ResponseObject($order->Order_ID, new MethodException(MethodException::ERROR_NO_CONTRACT, [$order->Order_ID_Contract])));
                continue;
            }
            if (empty($stock_store['ID'])) {
                $answer->addResponse(new ResponseObject($order->Order_ID, new MethodException(MethodException::ERROR_NO_STORE, [$order->Order_ID_Stock])));
                continue;
            }

            $result = OrderTable::createOrUpdateByField([
                'guid_id' => $order->Order_ID,
                'number' => $order->Order_Number,
                'order_date' =>  new DateTime((string)$order->Order_Date, 'Y-m-d H:i:s'),
                'contract_id' => $contract['id'],
                'store_guid' => $order->Order_ID_Stock,
                'staff_id' => $manager['id'] ?: 0,
                'account_id' => $account['id'] ?: 0,
                'note' => (string)$order->Order_Note,
                'type' => $order->Order_Form_Oper,
                'status' => $order->Order_Status,
                'sale_granted' => (bool)$order->Order_Sale_Granted,
                'sale_max_money' => (float)$order->Order_Max_Monet_Sale,
                'perm_reserve' => $order->Order_Reserve,
                'is_active' => !$order->deletion_mark,
            ], 'guid_id');

            if ($result->getErrorCollection()->count()) {
                $error = $result->getErrorCollection()->current();
                $answer->addResponse(new ResponseObject($order->Order_ID, new MethodException(MethodException::ERROR_BITRIX, [$error->getMessage(), $error->getCode()])));
            } else {
                if ($result->getId()) {
                    if ($result instanceof UpdateResult) {
                        $this->client->getLogger()->notice("Обновлена запись Заказа {$result->getId()} ({$order->Order_ID})");
                    } else {
                        $this->client->getLogger()->notice("Добавлена запись Заказа {$result->getId()} ({$order->Order_ID})");
                    }
                    $answer->addResponse($response = new ResponseObject($order->Order_ID));

                    try {
                        $this->client->OrderItem->updateOrderItems($order->Order_Items, $order->Order_ID);
                    } catch (MethodException $e) {
                        $response->setError($e);
                        OrderItemTable::deleteByOrderId($result->getId());
                        OrderTable::delete($result->getId());
                    }
                } else {
                    $answer->addResponse(new ResponseObject($order->Order_ID, new MethodException(MethodException::ERROR_UNDEFINED)));
                }
            }
        }
        return count($answer->Response);
    }
}