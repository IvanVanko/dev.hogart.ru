<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 06/10/2016
 * Time: 18:41
 */

namespace Hogart\Lk\Exchange\SOAP\Method;


use Bitrix\Main\Entity\UpdateResult;
use Bitrix\Main\Type\Date;
use Bitrix\Main\Type\DateTime;
use Hogart\Lk\Entity\AccountTable;
use Hogart\Lk\Entity\ContactTable;
use Hogart\Lk\Entity\OrderRTUTable;
use Hogart\Lk\Exchange\SOAP\AbstractMethod;
use Hogart\Lk\Exchange\SOAP\AbstractPutRequest;
use Hogart\Lk\Exchange\SOAP\MethodException;
use Hogart\Lk\Exchange\SOAP\Request;
use Hogart\Lk\Exchange\SOAP\Response;
use Hogart\Lk\Exchange\SOAP\ResponseObject;

class OrderRTU extends AbstractMethod
{
    /**
     * @inheritDoc
     */
    function getName()
    {
        return "OrderRTU";
    }

    public function orderRTUPut(AbstractPutRequest $request)
    {
        $response = $this->client->getSoapClient()->OrderRTUPut($request->__toRequest());
        foreach ($response->return->Response as $order_rtu) {
            OrderRTUTable::update($order_rtu->ID_Site, [
                'guid_id' => $order_rtu->ID
            ]);
        }
        return $response;
    }

    public function ordersRTUGet()
    {
        return $this->client->getSoapClient()->OrdersRTUGet(new Request());
    }

    public function ordersRTUAnswer(Response $response)
    {
        if (count($response->Response) && $this->is_answer) {
            return $this->client->getSoapClient()->OrdersRTUAnswer($response);
        }
    }

    public function updateOrdersRTU()
    {
        $answer = new Response();
        $response = $this->ordersRTUGet();
        foreach ($response->return->Order_RTU as $order_rtu) {
	        $account = AccountTable::getByField('guid_id', $order_rtu->Ord_RTU_ID_Account);
	        $contact = ContactTable::getByField('guid_id', $order_rtu->Ord_RTU_ID_Contact);

            $result = OrderRTUTable::createOrUpdateByField([
                "guid_id" => $order_rtu->Ord_RTU_ID,
                "number" => $order_rtu->Ord_RTU_Number,
                "rtu_date" => new DateTime((string)$order_rtu->Ord_RTU_Date, 'Y-m-d H:i:s'),
                "delivery_type" => intval($order_rtu->Ord_RTU_Delivery),
                "store_guid" => $order_rtu->Ord_RTU_Warehouse_ID,
                "address_guid" => $order_rtu->Ord_RTU_Address,
                "plan_date" => new Date($order_rtu->Ord_RTU_PlanDate, 'Y-m-d'),
                "plan_time" => (string)$order_rtu->Ord_RTU_PlanTime,
                "contact_id" => intval($contact['id']),
                "email" => (string)$order_rtu->Ord_RTU_Email,
                "phone" => (string)$order_rtu->Ord_RTU_Phone_Contact,
                "is_sms_notify" => (bool)$order_rtu->Ord_RTU_SendSMS,
                "is_email_notify" => (bool)$order_rtu->Ord_RTU_SendEmail,
                "is_tk" => (bool)$order_rtu->Ord_RTU_TK,
                "tk_name" => (string)$order_rtu->Ord_RTU_TK_Name,
                "tk_address" => $order_rtu->Ord_RTU_TK_Address,
                "is_active" => !$order_rtu->deletion_mark,
                "account_id" => intval($account['id'])
            ], 'guid_id');

            if ($result->getErrorCollection()->count()) {
                $error = $result->getErrorCollection()->current();
                $answer->addResponse(new ResponseObject($order_rtu->Ord_RTU_ID, new MethodException(MethodException::ERROR_BITRIX, [$error->getMessage(), $error->getCode()])));
            } else {
                if ($result->getId()) {
                    if ($result instanceof UpdateResult) {
                        $this->client->getLogger()->notice("Обновлена запись Заявка на отгрузку {$result->getId()} ({$order_rtu->Ord_RTU_ID})");
                    }

	                //@todo сделать добавление строк

                    $answer->addResponse(new ResponseObject($order_rtu->Ord_RTU_ID));
                } else {
                    $answer->addResponse(new ResponseObject($order_rtu->Ord_RTU_ID, new MethodException(MethodException::ERROR_UNDEFINED)));
                }
            }
        }
        $this->ordersRTUAnswer($answer);
        return count($answer->Response);
    }
}
