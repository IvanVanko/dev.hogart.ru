<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 02/08/16
 * Time: 17:29
 */

namespace Hogart\Lk\Exchange\RabbitMQ\Exchange;


use Hogart\Lk\Exchange\SOAP\Client;

class AddressExchange extends AbstractExchange
{
    /**
     * @inheritDoc
     */
    public function getDependencies()
    {
        return [
            __NAMESPACE__ . '\CompanyExchange',
            __NAMESPACE__ . '\HogartCompanyExchange',
        ];
    }

    /**
     * @inheritDoc
     */
    function getQueueName()
    {
        return "address";
    }

    /**
     * @inheritDoc
     */
    function runEnvelope(\AMQPEnvelope $envelope)
    {
        switch ($key = $this->getRoutingKey($envelope)) {
            case 'get':
                $count = Client::getInstance()->Address->updateAddresses();
                if (!empty($count)) {
                    $this
                        ->exchange
                        ->publish("", $this->getPublishKey($key), AMQP_NOPARAM, ["delivery_mode" => 2]);
                }
                break;
        }
    }

}