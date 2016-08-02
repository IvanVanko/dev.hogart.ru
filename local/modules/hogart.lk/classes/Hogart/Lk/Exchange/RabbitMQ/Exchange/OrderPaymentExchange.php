<?php
/**
 * Created by PhpStorm.
 * User: Ivan Kiselev aka ivan.kiselev[at]gmail.com
 * Date: 02/08/16
 * Time: 12:00
 */

namespace Hogart\Lk\Exchange\RabbitMQ\Exchange;


use Hogart\Lk\Exchange\RabbitMQ\EnvelopeException;
use Hogart\Lk\Exchange\RabbitMQ\Logger\BitrixLogger;

/**
 * Class OrderPaymentExchange - обмен с RQM по Оплатам (Payment)
 * @package Hogart\Lk\Exchange\RabbitMQ\Exchange
 */
class OrderPaymentExchange extends AbstractExchange
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
        return "order_payment";
    }

    /**
     * @inheritDoc
     */
    function runEnvelope(\AMQPEnvelope $envelope)
    {
        // some work with OrderPayment
        var_dump($this->getQueueName(), $envelope->getDeliveryTag());

    }
}