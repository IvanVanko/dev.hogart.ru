<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 02/08/16
 * Time: 17:29
 */

namespace Hogart\Lk\Exchange\RabbitMQ\Exchange;


use Hogart\Lk\Exchange\SOAP\AbstractPutRequest;
use Hogart\Lk\Exchange\SOAP\Client;

/**
 * Задачи RabbitMQ - Адреса
 *
 * @rabbitmq.exchange
 * | *__Код задачи__*           | *__Тело сообщения__* | *__Описание__*                    |
 * |:----------:                |:----------:          |--------------                     |
 * | __address.get__            |                      | _Задача получения Адресов из КИС_ |
 *
 * @package Hogart\Lk\Exchange\RabbitMQ\Exchange
 */
class AddressExchange extends AbstractExchange
{
    /**
     * {@inheritDoc}
     */
    public function getDependencies()
    {
        return [
            __NAMESPACE__ . '\AddressTypeExchange',
            __NAMESPACE__ . '\CompanyExchange',
            __NAMESPACE__ . '\HogartCompanyExchange',
        ];
    }

    /**
     * {@inheritDoc}
     */
    function getQueueName()
    {
        return "address";
    }

    /**
     * {@inheritDoc}
     */
    function runEnvelope(\AMQPEnvelope $envelope)
    {
        switch ($key = $this->getRoutingKey($envelope)) {
            case 'get':
                Client::getInstance()->Address->updateAddresses();
                break;
            case 'put':
                $request = unserialize($envelope->getBody());
                if ($request instanceof AbstractPutRequest) {
                    Client::getInstance()->Address->addressPut($request);
                }
                break;
        }
    }

}