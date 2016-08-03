<?php
/**
 * By: Ivan Kiselev aka shaqito[at]gmail.com
 * Via: PhpStorm.
 * At: 03.08.2016 16:34
 */

namespace Hogart\Lk\Exchange\RabbitMQ\Exchange;

use Hogart\Lk\Exchange\SOAP\Client;

class PaymentAccountExchange extends AbstractExchange
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
        return "payment_account";
    }

    /**
     * @inheritDoc
     */
    function runEnvelope(\AMQPEnvelope $envelope)
    {
        switch ($envelope->getRoutingKey()) {
            case 'payment_account.get':
                $count = Client::getInstance()->PaymentAccount->updatePaymentAccounts();
                if (!empty($count)) {
                    $this
                        ->exchange
                        ->publish("", "payment_account.get", AMQP_NOPARAM, ["delivery_mode" => 2]);
                }
                break;
        }
    }

}