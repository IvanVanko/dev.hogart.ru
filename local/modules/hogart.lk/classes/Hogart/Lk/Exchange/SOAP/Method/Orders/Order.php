<?php
/**
 * By: Ivan Kiselev aka shaqito[at]gmail.com
 * Via: PhpStorm.
 * At: 04.08.2016 10:01
 */

namespace Hogart\Lk\Exchange\SOAP\Method\Orders;

use Bitrix\Catalog\StoreTable;
use Hogart\Lk\Entity\OrderItemTable;
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
    const ERROR_NO_HOGART_COMPANY = 1;
    const ERROR_NO_CLIENT_COMPANY = 2;
    const ERROR_NO_CONTRACT = 3;
    const ERROR_NO_STORE = 4;
    
    protected static $errors = [
        self::ERROR_NO_HOGART_COMPANY => "Не найдена Компания Хогарт %s",
        self::ERROR_NO_CLIENT_COMPANY => "Не найдена Компания клиента %s",
        self::ERROR_NO_CONTRACT => "Не найден Договор клиента %s",
        self::ERROR_NO_STORE => "Не найден Склад %s",
    ];

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
        foreach ($orders as $order) {

            $hogart_company = HogartCompanyTable::getByField('guid_id', $order->Order_ID_Hogart);
            $client_company = CompanyTable::getByField('guid_id', $order->Order_ID_Company);
            $contract = ContractTable::getByField('guid_id', $order->Order_ID_Contract);
            $stock_store = StoreTable::getList(['filter' => ['=XML_ID' => $order->Order_ID_Stock]])->fetch();
            $manager = StaffTable::getByField('guid_id', $order->Order_ID_Staff);
            $account = AccountTable::getByField('user_guid_id', $order->Order_ID_Account);

            if (empty($hogart_company['id'])) {
                $answer->addResponse(new ResponseObject($order->Order_ID, new MethodException($this->getError(self::ERROR_NO_HOGART_COMPANY, [$order->Order_ID_Hogart]))));
                continue;
            }
            if (empty($client_company['id'])) {
                $answer->addResponse(new ResponseObject($order->Order_ID, new MethodException($this->getError(self::ERROR_NO_CLIENT_COMPANY, [$order->Order_ID_Company]))));
                continue;
            }
            if (empty($contract['id'])) {
                $answer->addResponse(new ResponseObject($order->Order_ID, new MethodException($this->getError(self::ERROR_NO_CONTRACT, [$order->Order_ID_Contract]))));
                continue;
            }
            if (empty($stock_store['ID'])) {
                $answer->addResponse(new ResponseObject($order->Order_ID, new MethodException($this->getError(self::ERROR_NO_STORE, [$order->Order_ID_Stock]))));
                continue;
            }

            $result = OrderTable::createOrUpdateByField([
                'guid_id' => $order->Order_ID,
                'company_id' => $client_company['id'],
                'hogart_company_id' => $hogart_company['id'],
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
                    $answer->addResponse($response = new ResponseObject($order->Order_ID));

                    try {
                        $this->client->OrderItem->updateOrderItems($order->Order_Items);
                    } catch (MethodException $e) {
                        $response->setError($e);
                        OrderItemTable::deleteByOrderId($result->getId());
                        OrderTable::delete($result->getId());
                    }
                } else {
                    $answer->addResponse(new ResponseObject($order->Order_ID, new MethodException(self::$default_errors[self::ERROR_UNDEFINED], self::ERROR_UNDEFINED)));
                }
            }
        }
        return count($answer->Response);
    }
}