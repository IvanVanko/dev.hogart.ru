<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 06/10/2016
 * Time: 17:18
 */

namespace Hogart\Lk\Exchange\RabbitMQ\Exchange;


use Hogart\Lk\Exchange\SOAP\AbstractPutRequest;
use Hogart\Lk\Exchange\SOAP\Client;

/**
 * Задачи RabbitMQ - Заявки на отгрузку
 *
 * @rabbitmq.exchange
 * | *__Код задачи__*           | *__Тело сообщения__* | *__Описание__*                           |
 * |:----------:                |:----------:          |--------------                            |
 * | __order_rtu.get__           |                      | _Задача получения Заявок на отгрузку из КИС_      |
 *
 * @package Hogart\Lk\Exchange\RabbitMQ\Exchange
 */
class OrderRTUExchange extends AbstractExchange
{
    /**
     * {@inheritDoc}
     */
    public function getDependencies()
    {
        return [
            __NAMESPACE__ . '\AddressExchange',
            __NAMESPACE__ . '\ContractExchange',
            __NAMESPACE__ . '\ContactExchange',
        ];
    }
    /**
     * @inheritDoc
     */
    function getQueueName()
    {
        return "order_rtu";
    }

    /**
     * @inheritDoc
     */
    function runEnvelope(\AMQPEnvelope $envelope)
    {
        switch ($key = $this->getRoutingKey($envelope)) {
            case 'get':
                Client::getInstance()->OrderRTU->updateOrdersRTU();
                break;
            case 'put':
                $request = unserialize($envelope->getBody());
                if ($request instanceof AbstractPutRequest) {
                    Client::getInstance()->OrderRTU->orderRTUPut($request);
                }
                break;
        }
    }
}
