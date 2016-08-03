<?php
/**
 * Created by PhpStorm.
 * User: Ivan Kiselev aka ivan.kiselev[at]gmail.com
 * Date: 02/08/16
 * Time: 12:00
 */

namespace Hogart\Lk\Exchange\RabbitMQ\Exchange;

use Hogart\Lk\Exchange\SOAP\Client;

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
        switch ($envelope->getRoutingKey()) {
            case 'order_payment.get':
                $count = Client::getInstance()->OrderPayment->updateOrderPayments();
                if (!empty($count)) {
                    $this
                        ->exchange
                        ->publish("", "order_payment.get", AMQP_NOPARAM, ["delivery_mode" => 2]);
                }
                break;
        }

    }
}