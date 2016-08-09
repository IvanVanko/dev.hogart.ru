<?php
/**
 * By: Ivan Kiselev aka shaqito@gmail.com
 * Using: PhpStorm.
 * Date: 05.08.2016 19:30
 */

namespace Hogart\Lk\Exchange\RabbitMQ\Exchange;

use Hogart\Lk\Exchange\SOAP\Client;

/**
 * Задачи RabbitMQ - Информация в контактах
 *
 * @rabbitmq.exchange
 * | *__Код задачи__*           | *__Тело сообщения__* | *__Описание__*                                         |
 * |:----------:                |:----------:          |--------------                                          |
 * | __contact_info.get__       |                      | _Задача получения Информации по Контактов из КИС_      |
 *
 * @package Hogart\Lk\Exchange\RabbitMQ\Exchange
 */
class ContactInfoExchange extends AbstractExchange
{
    /**
     * @inheritDoc
     */
    public function getDependencies()
    {
        return [
            __NAMESPACE__ . '\ContactExchange',
        ];
    }

    /**
     * @inheritDoc
     */
    function getQueueName()
    {
        return "contact_info";
    }

    /**
     * @inheritDoc
     */
    function runEnvelope(\AMQPEnvelope $envelope)
    {
        switch ($key = $this->getRoutingKey($envelope)) {
            case 'get':
                $count = Client::getInstance()->ContactInfo->updateContactsInfo();
                if (!empty($count)) {
                    $this
                        ->exchange
                        ->publish("", $this->getPublishKey($key), AMQP_NOPARAM, ["delivery_mode" => 2]);
                }
                break;
        }
    }

}