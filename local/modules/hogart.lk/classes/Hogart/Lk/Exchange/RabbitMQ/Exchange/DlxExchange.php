<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 16/11/2016
 * Time: 14:52
 */

namespace Hogart\Lk\Exchange\RabbitMQ\Exchange;


class DlxExchange extends AbstractExchange
{
    const DELAY_MIN = 2;

    /**
     * @inheritDoc
     */
    function getExchangeName()
    {
        return "hogart.lk.dlx";
    }

    /**
     * @inheritDoc
     */
    function getQueueName()
    {
        return "dlx";
    }

    /**
     * @inheritDoc
     */
    protected function declareQueue()
    {
        $this->queue = new \AMQPQueue($this->consumer->getChannel());
        $this->queue->setName($this->getQueueName());
        $this->queue->setFlags(AMQP_DURABLE);
        $this->queue->setArgument("x-message-ttl", self::DELAY_MIN * 1000 * 60);
        $this->queue->setArgument("x-dead-letter-exchange", "hogart.lk");
        $this->queue->declareQueue();
        $this->queue->bind($this->getExchangeName(), "#");
    }

    /**
     * @inheritDoc
     */
    function runEnvelope(\AMQPEnvelope $envelope)
    {
    }

    /**
     * @inheritDoc
     */
    function run()
    {
    }
}