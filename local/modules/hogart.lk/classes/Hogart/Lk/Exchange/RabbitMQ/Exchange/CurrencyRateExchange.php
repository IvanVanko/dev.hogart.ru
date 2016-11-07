<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 30/09/2016
 * Time: 19:28
 */

namespace Hogart\Lk\Exchange\RabbitMQ\Exchange;


use Hogart\Lk\Exchange\SOAP\Client;

class CurrencyRateExchange extends AbstractExchange
{
    /**
     * Получить имя очереди
     * @return string
     */
    function getQueueName()
    {
        return 'currencies';
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
                Client::getInstance()->CurrencyRate->updateCurrencyRates();
                break;
        }
    }

}