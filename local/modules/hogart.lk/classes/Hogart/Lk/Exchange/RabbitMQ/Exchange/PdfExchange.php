<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 10/10/2016
 * Time: 19:13
 */

namespace Hogart\Lk\Exchange\RabbitMQ\Exchange;


use Hogart\Lk\Exchange\SOAP\Client;

class PdfExchange extends AbstractExchange
{
    /**
     * @inheritDoc
     */
    function getQueueName()
    {
        return "pdf";
    }

    /**
     * @inheritDoc
     */
    function runEnvelope(\AMQPEnvelope $envelope)
    {
        switch ($key = $this->getRoutingKey($envelope)) {
            case 'request':
                $request = unserialize($envelope->getBody());
                Client::getInstance()->Pdf->getPdf($request);
                break;
        }
    }
}