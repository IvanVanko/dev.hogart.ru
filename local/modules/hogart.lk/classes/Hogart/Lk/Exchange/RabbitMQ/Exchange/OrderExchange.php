<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 04/08/16
 * Time: 01:57
 */

namespace Hogart\Lk\Exchange\RabbitMQ\Exchange;


use Hogart\Lk\Entity\OrderTable;
use Hogart\Lk\Exchange\SOAP\AbstractPutRequest;
use Hogart\Lk\Exchange\SOAP\Client;
use Hogart\Lk\Exchange\SOAP\Request\Order;

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
                Client::getInstance()->Orders->updateOrders();
                break;
            case 'put':
                $request = unserialize($envelope->getBody());
                if ($request instanceof AbstractPutRequest) {
                    Client::getInstance()->Orders->ordersPut($request);
                }
                break;
            case 'request':
                $order = OrderTable::getOrder(intval($envelope->getBody()));
                if (!empty($order)) {
                    Client::getInstance()->Orders->ordersPut(new Order([$order]));
                }
                break;
        }
    }

}