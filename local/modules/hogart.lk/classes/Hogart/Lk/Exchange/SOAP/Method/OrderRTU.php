<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 06/10/2016
 * Time: 18:41
 */

namespace Hogart\Lk\Exchange\SOAP\Method;


use Bitrix\Main\Entity\UpdateResult;
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
            $id = OrderRTUTable::getByField("guid_id", $order_rtu->Ord_RTU_ID)['id'];
            if ($id != $order_rtu->Ord_RTU_ID_Site) {
                $answer->addResponse(new ResponseObject($order_rtu->Ord_RTU_ID, new MethodException(MethodException::ERROR_NO_ORDER_RTU, [$order_rtu->Ord_RTU_ID])));
                continue;
            }

            $result = OrderRTUTable::update($id, [
                'number' => $order_rtu->Ord_RTU_Number
            ]);

            if ($result->getErrorCollection()->count()) {
                $error = $result->getErrorCollection()->current();
                $answer->addResponse(new ResponseObject($order_rtu->Ord_RTU_ID, new MethodException(MethodException::ERROR_BITRIX, [$error->getMessage(), $error->getCode()])));
            } else {
                if ($result->getId()) {
                    if ($result instanceof UpdateResult) {
                        $this->client->getLogger()->notice("Обновлена запись Заявка на отгрузку {$result->getId()} ({$order_rtu->Ord_RTU_ID})");
                    }
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
