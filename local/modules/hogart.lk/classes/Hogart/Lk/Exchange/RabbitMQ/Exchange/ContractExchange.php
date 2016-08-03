<?php
/**
 * By: Ivan Kiselev aka shaqito@gmail.com
 * Using: PhpStorm.
 * Date: 02.08.2016 19:24
 */

namespace Hogart\Lk\Exchange\RabbitMQ\Exchange;

use Hogart\Lk\Exchange\SOAP\Client;

class ContractExchange extends AbstractExchange
{
    /**
     * @inheritDoc
     */
    function getPriority()
    {
        return 100;
    }

    /**
     * @inheritDoc
     */
    function getQueueName()
    {
        return "contract";
    }

    /**
     * @inheritDoc
     */
    function runEnvelope(\AMQPEnvelope $envelope)
    {
        switch ($envelope->getRoutingKey()) {
            case 'contract.get':
                $count = Client::getInstance()->Contract->updateContracts();
                if (!empty($count)) {
                    $this
                        ->exchange
                        ->publish("", "contract.get", AMQP_NOPARAM, ["delivery_mode" => 2]);
                }
                break;
        }
    }

}