<?php
/**
 * By: Ivan Kiselev aka shaqito[at]gmail.com
 * Via: PhpStorm.
 * At: 03.08.2016 22:35
 */

namespace Hogart\Lk\Exchange\RabbitMQ\Exchange;


use Hogart\Lk\Exchange\SOAP\Client;

/**
 * Задачи RabbitMQ - Скидки на товары по компании
 *
 * @rabbitmq.exchange
 * | *__Код задачи__*           | *__Тело сообщения__* | *__Описание__*                   |
 * |:----------:                |:----------:          |--------------                    |
 * | __company_discount.get__   |                      | _Задача получения Скидок из КИС_ |
 *
 * @package Hogart\Lk\Exchange\RabbitMQ\Exchange
 */
class CompanyDiscountExchange extends AbstractExchange
{
    /**
     * {@inheritDoc}
     */
    public function getDependencies()
    {
        return [
            __NAMESPACE__ . '\CompanyExchange'
        ];
    }

    /**
     * {@inheritDoc}
     */
    function getQueueName()
    {
        return "company_discount";
    }

    /**
     * {@inheritDoc}
     */
    function runEnvelope(\AMQPEnvelope $envelope)
    {
        switch ($key = $this->getRoutingKey($envelope)) {
            case 'get':
                $count = Client::getInstance()->CompanyDiscount->updateCompanyDiscounts();
                if (!empty($count)) {
                    $this
                        ->exchange
                        ->publish("", $this->getPublishKey($key), AMQP_NOPARAM, ["delivery_mode" => 2]);
                }
                break;
        }
    }
}