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
        return $this->client->getSoapClient()->DocsOrderGet(new Request());
    }

    public function orderDocsAnswer(Response $response)
    {
        if (count($response->Response) && $this->is_answer) {
            return $this->client->getSoapClient()->DocsOrderAnswer($response);
        }
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
        $this->client->RTU->updateRTUs($response->return->RTU, $answer);
        $this->client->OrderPayment->updateOrderPayments($response->return->Payment, $answer);
        $this->orderDocsAnswer($answer);
        return count($answer->Response);
    }
}