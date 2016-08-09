<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 04/08/16
 * Time: 01:51
 */

namespace Hogart\Lk\Exchange\RabbitMQ\Exchange;


use Hogart\Lk\Exchange\SOAP\Client;

/**
 * Задачи RabbitMQ - Документов заказа
 *
 * @rabbitmq.exchange
 * | *__Код задачи__*           | *__Тело сообщения__* | *__Описание__*                               |
 * |:----------:                |:----------:          |--------------                                |
 * | __order_docs.get__         |                      | _Задача получения Документов заказов из КИС_ |
 *
 * @package Hogart\Lk\Exchange\RabbitMQ\Exchange
 */
class OrderDocsExchange extends AbstractExchange
{
    /**
     * @inheritDoc
     */
    public function getDependencies()
    {
        return [
            __NAMESPACE__ . '\OrderExchange'
        ];
    }

    /**
     * @inheritDoc
     */
    function getQueueName()
    {
        return "order_docs";
    }

    /**
     * @inheritDoc
     */
    function runEnvelope(\AMQPEnvelope $envelope)
    {
        switch ($key = $this->getRoutingKey($envelope)) {
            case 'get':
                $count = Client::getInstance()->OrderDocs->updateOrderDocs();
                if (!empty($count)) {
                    $this
                        ->exchange
                        ->publish("", $this->getPublishKey($key), AMQP_NOPARAM, ["delivery_mode" => 2]);
                }
                break;
        }
    }

}