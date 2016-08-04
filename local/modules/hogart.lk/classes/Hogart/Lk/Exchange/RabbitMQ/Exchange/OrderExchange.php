<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 04/08/16
 * Time: 01:57
 */

namespace Hogart\Lk\Exchange\RabbitMQ\Exchange;


class OrderExchange extends AbstractExchange
{
    /**
     * @inheritDoc
     */
    function getQueueName()
    {
        return "order";
    }

    /**
     * @inheritDoc
     */
    function runEnvelope(\AMQPEnvelope $envelope)
    {
        // TODO: Implement runEnvelope() method.
    }

}