<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 04/08/16
 * Time: 01:23
 */

namespace Hogart\Lk\Exchange\RabbitMQ\Exchange;


use Hogart\Lk\Exchange\SOAP\Client;

/**
 * Задачи RabbitMQ - Компании ГК Хогарт
 *
 * @rabbitmq.exchange
 * | *__Код задачи__*           | *__Тело сообщения__* | *__Описание__*                             |
 * |:----------:                |:----------:          |--------------                              |
 * | __hogart_company.get__     |                      | _Задача получения Компаний Хогарта из КИС_ |
 *
 * @package Hogart\Lk\Exchange\RabbitMQ\Exchange
 */
class HogartCompanyExchange extends AbstractExchange
{
    /**
     * {@inheritDoc}
     */
    public function getDependencies()
    {
        return [
            __NAMESPACE__ . '\StaffExchange'
        ];
    }

    /**
     * {@inheritDoc}
     */
    function getQueueName()
    {
        return "hogart_company";
    }

    /**
     * {@inheritDoc}
     */
    function runEnvelope(\AMQPEnvelope $envelope)
    {
        switch ($key = $this->getRoutingKey($envelope)) {
            case 'get':
                $count = Client::getInstance()->HogartCompany->updateHogartCompanies();
                if (!empty($count)) {
                    $this
                        ->exchange
                        ->publish("", $this->getPublishKey($key), AMQP_NOPARAM, ["delivery_mode" => 2]);
                }
                break;
        }
    }
}