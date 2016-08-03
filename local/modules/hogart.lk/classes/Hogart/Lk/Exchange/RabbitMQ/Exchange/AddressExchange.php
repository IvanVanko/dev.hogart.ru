<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 02/08/16
 * Time: 17:29
 */

namespace Hogart\Lk\Exchange\RabbitMQ\Exchange;


use Hogart\Lk\Exchange\SOAP\Client;

class AddressExchange extends AbstractExchange
{
    /**
     * @inheritDoc
     */
    function getPriority()
    {
        return "98";
    }

    /**
     * @inheritDoc
     */
    function getQueueName()
    {
        return "address";
    }

    /**
     * @inheritDoc
     */
    function runEnvelope(\AMQPEnvelope $envelope)
    {
        switch ($envelope->getRoutingKey()) {
            case 'address.get':
                $count = Client::getInstance()->Address->updateAddresses();
                if (!empty($count)) {
                    $this
                        ->exchange
                        ->publish("", "address.get", AMQP_NOPARAM, ["delivery_mode" => 2]);
                }
                break;
        }
    }

}