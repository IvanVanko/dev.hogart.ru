<?php
/**
 * Created by PhpStorm.
 * User: Ivan Kiselev aka ivan.kiselev[at]gmail.com
 * Date: 02/08/16
 * Time: 12:15
 */

namespace Hogart\Lk\Exchange\RabbitMQ\Exchange;


use Hogart\Lk\Exchange\RabbitMQ\EnvelopeException;
use Hogart\Lk\Exchange\RabbitMQ\Logger\BitrixLogger;

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
        // some work with RTU, RTUItem
        var_dump($this->getQueueName(), $envelope->getDeliveryTag());

    }
}