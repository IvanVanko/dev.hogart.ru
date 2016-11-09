<?php
/**
 * By: Ivan Kiselev aka shaqito@gmail.com
 * Using: PhpStorm.
 * Date: 05.08.2016 16:24
 */

namespace Hogart\Lk\Exchange\RabbitMQ\Exchange;

use Hogart\Lk\Exchange\SOAP\AbstractPutRequest;
use Hogart\Lk\Exchange\SOAP\Client;

/**
 * Задачи RabbitMQ - Контакты
 *
 * @rabbitmq.exchange
 * | *__Код задачи__*          | *__Тело сообщения__* | *__Описание__*                                         |
 * |:----------:               |:----------:           |--------------                                          |
 * | __contact.get__           |                       | _Задача получения Контактов из КИС_                   |
 *
 * @package Hogart\Lk\Exchange\RabbitMQ\Exchange
 */
class ContactExchange extends AbstractExchange
{
    /**
     * {@inheritDoc}
     */
    public function getDependencies()
    {
        return [
            __NAMESPACE__ . '\HogartCompanyExchange',
        ];
    }

    /**
     * {@inheritDoc}
     */
    function getQueueName()
    {
        return "contact";
    }

    /**
     * {@inheritDoc}
     */
    function runEnvelope(\AMQPEnvelope $envelope)
    {
        switch ($key = $this->getRoutingKey($envelope)) {
            case 'get':
                Client::getInstance()->Contact->updateContacts();
                break;
            case 'put':
                $request = unserialize($envelope->getBody());
                if ($request instanceof AbstractPutRequest) {
                    Client::getInstance()->Contact->contactsPut($request);
                }
                break;
        }
    }

}