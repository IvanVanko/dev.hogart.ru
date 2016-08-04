<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 04/08/16
 * Time: 01:32
 */

namespace Hogart\Lk\Exchange\SOAP\Method;

use Hogart\Lk\Exchange\SOAP\AbstractMethod;

class OrderDocs extends AbstractMethod
{
    /**
     * @inheritDoc
     */
    function getName()
    {
        return "OrderDocs";
    }

    public function getOrderDocs()
    {
        return $this->client->getSoapClient()->Docs_OrderGet(new Request());
    }

    public function orderDocsAnswer(Response $response)
    {
        return $this->client->getSoapClient()->Docs_OrderAnswer($response);
    }

    /**
     * @todo Доработать после появления метода Docs_Order
     *
     * @return int
     */
    public function updateOrderDocs()
    {
        $answer = new Response();
        $response = $this->getOrderDocs();
        $this->client->RTU->updateRTUs($response->return->RTU->RTUHeaders, $answer);
        $this->client->RTUItem->updateRTUItems($response->return->RTU->RTUItems, $answer);
        $this->client->OrderPayment->updateOrderPayments($response->return->Payment, $answer);

        return count($answer->Response);
    }
}