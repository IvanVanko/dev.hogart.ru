<?php
/**
 * By: Ivan Kiselev aka shaqito[at]gmail.com
 * Via: PhpStorm.
 * At: 03.08.2016 22:51
 */

namespace Hogart\Lk\Exchange\RabbitMQ\Exchange;

use Hogart\Lk\Exchange\SOAP\Client;

/**
 * Class RTUItemExchange - Обмен RMQ по Элементам реализации отгрузки (RTU, RTU_Item)
 * @package Hogart\Lk\Exchange\RabbitMQ\Exchange
 */
class RTUItemExchange extends AbstractExchange
{
    /**
     * @inheritDoc
     */
    function getPriority()
    {
        return 1000;
    }

    /**
     * @inheritDoc
     */
    function getQueueName()
    {
        return "rtu_item";
    }

    /**
     * @inheritDoc
     */
    function runEnvelope(\AMQPEnvelope $envelope)
    {
        switch ($envelope->getRoutingKey()) {
            case 'rtu.get':
                $count = Client::getInstance()->RTUItem->updateRTUItems();
                if (!empty($count)) {
                    $this
                        ->exchange
                        ->publish("", "rtu.get", AMQP_NOPARAM, ["delivery_mode" => 2]);
                }
                break;
        }
    }
}