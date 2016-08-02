<?php
/**
 * By: Ivan Kiselev aka shaqito@gmail.com
 * Using: PhpStorm.
 * Date: 02.08.2016 19:24
 */

namespace Hogart\Lk\Exchange\RabbitMQ\Exchange;


use Hogart\Lk\Exchange\SOAP\Client;

class CompanyExchange extends AbstractExchange
{
    /**
     * @inheritDoc
     */
    function getPriority()
    {
        return 96;
    }

    /**
     * @inheritDoc
     */
    function getQueueName()
    {
        return "company";
    }

    /**
     * @inheritDoc
     */
    function runEnvelope(\AMQPEnvelope $envelope)
    {
        switch ($envelope->getRoutingKey()) {
            case 'company.get':
                $count = Client::getInstance()->Company->updateCompanies();
                if (!empty($count)) {
                    $this
                        ->exchange
                        ->publish("", "company.get", AMQP_NOPARAM, ["delivery_mode" => 2]);
                }
                break;
        }
    }

}