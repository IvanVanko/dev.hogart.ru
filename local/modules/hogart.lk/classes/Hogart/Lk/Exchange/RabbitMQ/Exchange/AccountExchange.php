<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 31/07/16
 * Time: 00:45
 */

namespace Hogart\Lk\Exchange\RabbitMQ\Exchange;


use Hogart\Lk\Exchange\SOAP\Client;

class AccountExchange extends AbstractExchange
{
    /**
     * @inheritDoc
     */
    function getPriority()
    {
        return 99;
    }

    /**
     * @inheritDoc
     */
    function getQueueName()
    {
        return "account";
    }

    /**
     * @inheritDoc
     */
    function runEnvelope(\AMQPEnvelope $envelope)
    {
        switch ($envelope->getRoutingKey()) {
            case 'account.get':
                $count = Client::getInstance()->Account->createOrUpdateAccounts();
                if (!empty($count)) {
                    $this
                        ->exchange
                        ->publish("", "account.get", AMQP_NOPARAM, ["delivery_mode" => 2]);
                }
                break;
        }
    }

}