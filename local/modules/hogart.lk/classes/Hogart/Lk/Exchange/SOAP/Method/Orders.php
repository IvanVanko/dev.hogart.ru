<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 04/08/16
 * Time: 01:32
 */

namespace Hogart\Lk\Exchange\SOAP\Method;

use Hogart\Lk\Exchange\SOAP\AbstractMethod;
use Hogart\Lk\Exchange\SOAP\Request;
use Hogart\Lk\Exchange\SOAP\Response;

class Orders extends AbstractMethod
{
    /**
     * @inheritDoc
     */
    function getName()
    {
        return "Orders";
    }

    /**
     * Получение Заказов от КИС
     * @return mixed
     */
    public function ordersGet()
    {
        return $this->client->getSoapClient()->OrdersGet(new Request());
    }

    /**
     * Ответ в КИС о полученных Заказах
     * @param Response $response
     * @return mixed
     */
    public function ordersAnswer(Response $response)
    {
        if (count($response->Response) && $this->is_answer) {
            return $this->client->getSoapClient()->OrdersAnswer($response);
        }
    }

    /**
     * Обработка полученных от КИС Заказов
     * @return int
     */
    public function updateOrders()
    {
        $answer = new Response();
        $response = $this->ordersGet();
        $this->client->Order->updateOrders($response->return->Order, $answer);
        $this->ordersAnswer($answer);
        return count($answer->Response);
    }
}