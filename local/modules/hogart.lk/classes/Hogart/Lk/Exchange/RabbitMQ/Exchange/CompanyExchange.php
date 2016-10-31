<?php
/**
 * By: Ivan Kiselev aka shaqito@gmail.com
 * Using: PhpStorm.
 * Date: 02.08.2016 19:24
 */

namespace Hogart\Lk\Exchange\RabbitMQ\Exchange;


use Hogart\Lk\Exchange\SOAP\AbstractPutRequest;
use Hogart\Lk\Exchange\SOAP\Client;

/**
 * Задачи RabbitMQ - Компании
 *
 * @rabbitmq.exchange
 * | *__Код задачи__*           | *__Тело сообщения__* | *__Описание__*                                         |
 * |:----------:                |:----------:           |--------------                                          |
 * | __company.get__            |                       | _Задача получения Компаний из КИС_                   |
 *
 * @package Hogart\Lk\Exchange\RabbitMQ\Exchange
 */
class CompanyExchange extends AbstractExchange
{
    /**
     * {@inheritDoc}
     */
    public function getDependencies()
    {
        return [
            __NAMESPACE__ . '\ContactExchange',
            __NAMESPACE__ . '\HogartCompanyExchange',
        ];
    }
    /**
     * {@inheritDoc}
     */
    function getQueueName()
    {
        return "company";
    }

    /**
     * {@inheritDoc}
     */
    function runEnvelope(\AMQPEnvelope $envelope)
    {
        switch ($key = $this->getRoutingKey($envelope)) {
            case 'get':
                Client::getInstance()->Company->updateCompanies();
                break;
            case 'put':
                $request = unserialize($envelope->getBody());
                if ($request instanceof AbstractPutRequest) {
                    Client::getInstance()->Company->companyPut($request);
                }
                break;
        }
    }
}