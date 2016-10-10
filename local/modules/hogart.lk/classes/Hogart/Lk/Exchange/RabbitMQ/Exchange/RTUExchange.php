<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 06/10/2016
 * Time: 17:17
 */

namespace Hogart\Lk\Exchange\RabbitMQ\Exchange;


use Hogart\Lk\Exchange\SOAP\Client;

/**
 * Задачи RabbitMQ - Отгрузки
 *
 * @rabbitmq.exchange
 * | *__Код задачи__*           | *__Тело сообщения__* | *__Описание__*                           |
 * |:----------:                |:----------:          |--------------                            |
 * | __rtu.get__           |                      | _Задача получения Отгрузки из КИС_      |
 *
 * @package Hogart\Lk\Exchange\RabbitMQ\Exchange
 */
class RTUExchange extends AbstractExchange
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
     * Получить имя очереди
     * @return string
     */
    function getQueueName()
    {
        return "rtu";
    }

    /**
     * Обработать сообщение
     * @param \AMQPEnvelope $envelope
     * @return mixed
     */
    function runEnvelope(\AMQPEnvelope $envelope)
    {
        switch ($key = $this->getRoutingKey($envelope)) {
            case 'get':
                $count = Client::getInstance()->RTU->updateRTUs();
                if (!empty($count)) {
                    $this
                        ->publish("", $key);
                }
                break;
        }
    }

}