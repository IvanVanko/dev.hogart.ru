<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 30/07/16
 * Time: 22:33
 */

namespace Hogart\Lk\Exchange\RabbitMQ\Exchange;


use Hogart\Lk\Exchange\RabbitMQ\EnvelopeException;
use Hogart\Lk\Exchange\RabbitMQ\Logger\BitrixLogger;

class StaffExchange extends AbstractExchange
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
        return "staff";
    }

    /**
     * @inheritDoc
     */
    function runEnvelope(\AMQPEnvelope $envelope)
    {
        // some work
        var_dump($this->getQueueName(), $envelope->getDeliveryTag());
        throw new EnvelopeException("Test exception");
    }
}