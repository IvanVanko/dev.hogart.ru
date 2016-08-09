<?php
/**
 * By: Ivan Kiselev aka shaqito[at]gmail.com
 * Via: PhpStorm.
 * At: 03.08.2016 16:34
 */

namespace Hogart\Lk\Exchange\RabbitMQ\Exchange;

use Hogart\Lk\Exchange\SOAP\Client;

/**
 * Задачи RabbitMQ - Счета
 *
 * @rabbitmq.exchange
 * | *__Код задачи__*           | *__Тело сообщения__* | *__Описание__*                    |
 * |:----------:                |:----------:          |--------------                     |
 * | __payment_account.get__    |                      | _Задача получения Счетов из КИС_  |
 *
 * @package Hogart\Lk\Exchange\RabbitMQ\Exchange
 */
class PaymentAccountExchange extends AbstractExchange
{
    /**
     * {@inheritDoc}
     */
    public function getDependencies()
    {
        return [
            __NAMESPACE__ . '\CompanyExchange',
            __NAMESPACE__ . '\HogartCompanyExchange',
        ];
    }

    /**
     * {@inheritDoc}
     */
    function getQueueName()
    {
        return "payment_account";
    }

    /**
     * {@inheritDoc}
     */
    function runEnvelope(\AMQPEnvelope $envelope)
    {
        switch ($key = $this->getRoutingKey($envelope)) {
            case 'get':
                $count = Client::getInstance()->PaymentAccount->updatePaymentAccounts();
                if (!empty($count)) {
                    $this
                        ->exchange
                        ->publish("", $this->getPublishKey($key), AMQP_NOPARAM, ["delivery_mode" => 2]);
                }
                break;
        }
    }

}