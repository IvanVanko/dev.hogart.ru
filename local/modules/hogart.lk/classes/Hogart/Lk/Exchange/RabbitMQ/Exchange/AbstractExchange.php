<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 30/07/16
 * Time: 22:24
 */

namespace Hogart\Lk\Exchange\RabbitMQ\Exchange;


use Hogart\Lk\Exchange\RabbitMQ\EnvelopeException;
use Hogart\Lk\Exchange\RabbitMQ\Consumer;

abstract class AbstractExchange implements ExchangeInterface
{
    /** @var Consumer */
    protected $consumer;
    /** @var \AMQPExchange */
    protected $exchange;
    /** @var \AMQPQueue */
    protected $queue;

    /**
     * @inheritDoc
     */
    function useConsumer(Consumer $consumer)
    {
        $this->consumer = $consumer;
        $this
            ->declareExchange()
            ->declareQueue();

        return $this;
    }

    /**
     * @inheritDoc
     */
    function getExchangeName()
    {
        return "hogart.lk";
    }

    /**
     * @return $this
     */
    protected function declareExchange()
    {
        $this->exchange = new \AMQPExchange($this->consumer->getChannel());
        $this->exchange->setName($this->getExchangeName());
        $this->exchange->setType(AMQP_EX_TYPE_TOPIC);
        $this->exchange->setFlags(AMQP_DURABLE);
        $this->exchange->declareExchange();

        return $this;
    }

    /**
     * @return $this
     */
    protected function declareQueue()
    {
        $this->queue = new \AMQPQueue($this->consumer->getChannel());
        $this->queue->setName($this->getQueueName());
        $this->queue->setFlags(AMQP_DURABLE);
        $this->queue->declareQueue();
        $this->queue->bind($this->getExchangeName(), "{$this->getQueueName()}.#");
        
        return $this;
    }

    /**
     * @inheritDoc
     */
    function run()
    {
        while ($message = $this->queue->get()) {
            if (!is_object($message)) {
                continue;
            }
            try {
                $this->runEnvelope($message);
                $this->queue->ack($message->getDeliveryTag());
                $this->consumer->getLogger()->notice("Задача {$message->getRoutingKey()} обработана");
            } catch (EnvelopeException $e) {
                $this->queue->nack($message->getDeliveryTag());
                $this->consumer->getLogger()->error("Ошибка обработки задачи {$message->getRoutingKey()}: {$e->getMessage()}");
            } catch (\Exception $e) {
                $this->consumer->getLogger()->error("Ошибка обработки задачи {$message->getRoutingKey()}: {$e->getMessage()}");
            }
        }
    }

    /**
     * @return Consumer
     */
    public function getConsumer()
    {
        return $this->consumer;
    }

    /**
     * @return \AMQPExchange
     */
    public function getExchange()
    {
        return $this->exchange;
    }

    /**
     * @return \AMQPQueue
     */
    public function getQueue()
    {
        return $this->queue;
    }
    
}
