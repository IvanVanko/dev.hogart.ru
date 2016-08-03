<?php
/**
 * By: Ivan Kiselev aka shaqito[at]gmail.com
 * Via: PhpStorm.
 * At: 03.08.2016 22:35
 */

namespace Hogart\Lk\Exchange\RabbitMQ\Exchange;


use Hogart\Lk\Exchange\SOAP\Client;

class CompanyDiscountExchange extends AbstractExchange
{
    /**
     * @inheritDoc
     */
    function getPriority()
    {
        return 100;
    }

    /**
     * @inheritDoc
     */
    function getQueueName()
    {
        return "company_discount";
    }

    /**
     * @inheritDoc
     */
    function runEnvelope(\AMQPEnvelope $envelope)
    {
        switch ($envelope->getRoutingKey()) {
            case 'company_discount.get':
                $count = Client::getInstance()->CompanyDiscount->updateCompanyDiscounts();
                if (!empty($count)) {
                    $this
                        ->exchange
                        ->publish("", "company_discount.get", AMQP_NOPARAM, ["delivery_mode" => 2]);
                }
                break;
        }
    }
}