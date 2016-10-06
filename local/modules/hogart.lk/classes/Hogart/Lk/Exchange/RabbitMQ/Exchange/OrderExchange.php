<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 04/08/16
 * Time: 01:57
 */

namespace Hogart\Lk\Exchange\RabbitMQ\Exchange;


use Hogart\Lk\Exchange\SOAP\AbstractPutRequest;
use Hogart\Lk\Exchange\SOAP\Client;

/**
 * Задачи RabbitMQ - Заказы
 *
 * @rabbitmq.exchange
 * | *__Код задачи__*           | *__Тело сообщения__* | *__Описание__*                    |
 * |:----------:                |:----------:          |--------------                     |
 * | __order.get__              |                      | _Задача получения Заказов из КИС_ |
 *
 * @package Hogart\Lk\Exchange\RabbitMQ\Exchange
 */
class OrderExchange extends AbstractExchange
{
    /**
     * {@inheritDoc}
     */
    public function getDependencies()
    {
        return [
            __NAMESPACE__ . '\HogartCompanyExchange',
            __NAMESPACE__ . '\CompanyExchange',
            __NAMESPACE__ . '\AccountExchange',
            __NAMESPACE__ . '\StaffExchange'
        ];
    }

    /**
     * {@inheritDoc}
     */
    function getQueueName()
    {
        return "order";
    }

    /**
     * {@inheritDoc}
     */
    function runEnvelope(\AMQPEnvelope $envelope)
    {
        switch ($key = $this->getRoutingKey($envelope)) {
            case 'get':
                $count = Client::getInstance()->Orders->updateOrders();
                if (!empty($count)) {
                    $this
                        ->exchange
                        ->publish("", $this->getPublishKey($key), AMQP_NOPARAM, ["delivery_mode" => 2]);
                }
                break;
            case 'put':
                $request = unserialize($envelope->getBody());
                if ($request instanceof AbstractPutRequest) {
                    Client::getInstance()->Orders->ordersPut($request);
                }
                break;
        }
    }

}