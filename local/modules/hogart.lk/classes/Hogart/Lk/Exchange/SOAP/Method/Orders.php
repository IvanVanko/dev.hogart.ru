<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 04/08/16
 * Time: 01:32
 */

namespace Hogart\Lk\Exchange\SOAP\Method;

use Hogart\Lk\Exchange\SOAP\AbstractMethod;

class Orders extends AbstractMethod
{
    /**
     * @inheritDoc
     */
    function getName()
    {
        return "Orders";
    }

    public function getOrders()
    {
        return $this->client->getSoapClient()->OrdersGet(new Request());
    }

    public function ordersAnswer(Response $response)
    {
        if (count($response->Response) && $this->is_answer) {
            return $this->client->getSoapClient()->OrdersAnswer($response);
        }
    }

    /**
     * @todo Доработать после появления метода Docs_Order
     *
     * @return int
     */
    public function updateOrders()
    {
        $answer = new Response();
        $response = $this->getOrders();
        $this->client->Order->updateOrders($response->return->Order, $answer);
        $this->ordersAnswer($answer);
        return count($answer->Response);
    }
}