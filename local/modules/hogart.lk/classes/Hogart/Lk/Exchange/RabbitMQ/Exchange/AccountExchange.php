<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 31/07/16
 * Time: 00:45
 */

namespace Hogart\Lk\Exchange\RabbitMQ\Exchange;


class AccountExchange extends AbstractExchange
{
    /**
     * @inheritDoc
     */
    function getPriority()
    {
        return 99;
    }

    /**
     * @inheritDoc
     */
    function getQueueName()
    {
        return "account";
    }

    /**
     * @inheritDoc
     */
    function runEnvelope(\AMQPEnvelope $envelope)
    {
        // some work
        var_dump($this->getQueueName(), $envelope->getDeliveryTag());
    }

}