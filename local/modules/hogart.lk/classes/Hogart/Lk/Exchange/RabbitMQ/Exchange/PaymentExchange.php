<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 07/10/2016
 * Time: 12:29
 */

namespace Hogart\Lk\Exchange\RabbitMQ\Exchange;


use Hogart\Lk\Exchange\SOAP\Client;

/**
 * Задачи RabbitMQ - Платежи
 *
 * @rabbitmq.exchange
 * | *__Код задачи__*           | *__Тело сообщения__* | *__Описание__*                           |
 * |:----------:                |:----------:          |--------------                            |
 * | __payment.get__           |                      | _Задача получения Платежей из КИС_      |
 *
 * @package Hogart\Lk\Exchange\RabbitMQ\Exchange
 */
class PaymentExchange extends AbstractExchange
{
    /**
     * {@inheritDoc}
     */
    public function getDependencies()
    {
        return [
            __NAMESPACE__ . '\OrderExchange',
        ];
    }

    /**
     * @inheritDoc
     */
    function getQueueName()
    {
        return "payment";
    }

    /**
     * @inheritDoc
     */
    function runEnvelope(\AMQPEnvelope $envelope)
    {
        switch ($key = $this->getRoutingKey($envelope)) {
            case 'get':
                Client::getInstance()->Payment->updatePayments();
                break;
        }
    }

}