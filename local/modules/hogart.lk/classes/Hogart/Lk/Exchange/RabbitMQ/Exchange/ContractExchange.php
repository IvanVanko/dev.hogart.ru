<?php
/**
 * By: Ivan Kiselev aka shaqito@gmail.com
 * Using: PhpStorm.
 * Date: 02.08.2016 19:24
 */

namespace Hogart\Lk\Exchange\RabbitMQ\Exchange;

use Hogart\Lk\Exchange\SOAP\Client;

/**
 * Задачи RabbitMQ - Договора
 *
 * @rabbitmq.exchange
 * | *__Код задачи__*           | *__Тело сообщения__* | *__Описание__*                           |
 * |:----------:                |:----------:          |--------------                            |
 * | __contract.get__           |                      | _Задача получения Договоров из КИС_      |
 *
 * @package Hogart\Lk\Exchange\RabbitMQ\Exchange
 */
class ContractExchange extends AbstractExchange
{
    /**
     * @inheritDoc
     */
    public function getDependencies()
    {
        return [
            __NAMESPACE__ . '\HogartCompanyExchange',
            __NAMESPACE__ . '\CompanyExchange',
        ];
    }

    /**
     * @inheritDoc
     */
    function getQueueName()
    {
        return "contract";
    }

    /**
     * @inheritDoc
     */
    function runEnvelope(\AMQPEnvelope $envelope)
    {
        switch ($key = $this->getRoutingKey($envelope)) {
            case 'get':
                $count = Client::getInstance()->Contract->updateContracts();
                if (!empty($count)) {
                    $this
                        ->exchange
                        ->publish("", $this->getPublishKey($key), AMQP_NOPARAM, ["delivery_mode" => 2]);
                }
                break;
        }
    }

}