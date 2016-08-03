<?php
/**
 * Created by PhpStorm.
 * User: Ivan Kiselev aka ivan.kiselev[at]gmail.com
 * Date: 02/08/16
 * Time: 12:15
 */

namespace Hogart\Lk\Exchange\RabbitMQ\Exchange;


use Hogart\Lk\Exchange\RabbitMQ\EnvelopeException;
use Hogart\Lk\Exchange\SOAP\Client;

/**
 * Class RTUExchange - Обмен RMQ по Реализациям отгрузки (RTU, RTU_Item)
 * @package Hogart\Lk\Exchange\RabbitMQ\Exchange
 */
class RTUExchange extends AbstractExchange
{
    /**
     * @inheritDoc
     */
    function getPriority()
    {
        return 1000;
    }

    /**
     * @inheritDoc
     */
    function getQueueName()
    {
        return "rtu";
    }

    /**
     * @inheritDoc
     */
    function runEnvelope(\AMQPEnvelope $envelope)
    {
        switch ($envelope->getRoutingKey()) {
            case 'rtu.get':
                $count = Client::getInstance()->RTU->updateRTUs();
                if (!empty($count)) {
                    $this
                        ->exchange
                        ->publish("", "rtu.get", AMQP_NOPARAM, ["delivery_mode" => 2]);
                }
                break;
        }
    }
}