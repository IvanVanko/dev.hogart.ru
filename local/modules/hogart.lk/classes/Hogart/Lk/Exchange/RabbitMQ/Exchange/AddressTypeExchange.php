<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 07/10/2016
 * Time: 16:38
 */

namespace Hogart\Lk\Exchange\RabbitMQ\Exchange;


use Hogart\Lk\Exchange\SOAP\Client;

/**
 * Задачи RabbitMQ - Типы Адресов
 *
 * @rabbitmq.exchange
 * | *__Код задачи__*           | *__Тело сообщения__* | *__Описание__*                    |
 * |:----------:                |:----------:          |--------------                     |
 * | __address_type.get__       |                      | _Задача получения Типов Адресов из КИС_ |
 *
 * @package Hogart\Lk\Exchange\RabbitMQ\Exchange
 */
class AddressTypeExchange extends AbstractExchange
{
    /**
     * @inheritDoc
     */
    function getQueueName()
    {
        return "address_type";
    }

    /**
     * @inheritDoc
     */
    function runEnvelope(\AMQPEnvelope $envelope)
    {
        switch ($key = $this->getRoutingKey($envelope)) {
            case 'get':
                $count = Client::getInstance()->AddressType->updateAddressTypes();
                if (!empty($count)) {
                    $this
                        ->publish("", $key);
                }
                break;
        }
    }

}