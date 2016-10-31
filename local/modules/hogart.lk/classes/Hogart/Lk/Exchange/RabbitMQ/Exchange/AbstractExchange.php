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

/**
 * Абстрактный класс Задачи RabbitMQ
 * @package Hogart\Lk\Exchange\RabbitMQ\Exchange
 */
abstract class AbstractExchange implements ExchangeInterface
{
    /** @var Consumer */
    protected $consumer;
    /** @var \AMQPExchange */
    protected $exchange;
    /** @var \AMQPQueue */
    protected $queue;
    /** @var array  */
    protected $dependencies = [];

    /**
     * AbstractExchange constructor.
     * @param Consumer $consumer
     */
    public function __construct(Consumer $consumer = null)
    {
        if (null !== $consumer) {
            $this->useConsumer($consumer);
        }
    }


    /**
     * Получить ключ для публикации сообщения
     * @param string $key Сокращенный ключ
     * @return string
     */
    public function getPublishKey($key)
    {
        return $this->getQueueName() . "." . $key;
    }

    /**
     * Получить обработанный ключ обмена, зависящий от имени очереди
     * @param \AMQPEnvelope $envelope
     * @return string Сокращенный ключ
     */
    public function getRoutingKey(\AMQPEnvelope $envelope)
    {
        return preg_replace("%^" . $this->getQueueName() . "\.%", "", $envelope->getRoutingKey());
    }

    /**
     * {@inheritDoc}
     */
    public function getDependencies()
    {
        return $this->dependencies;
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     */
    function getExchangeName()
    {
        return "hogart.lk";
    }

    /**
     * Объявить точку обмена
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
     * Объявить очередь и привязать к точке обмена
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
     * {@inheritDoc}
     */
    function run()
    {
        while ($message = $this->queue->get()) {
            if (!is_object($message)) {
                continue;
            }
            try {
                $this->consumer->getLogger()->notice("Старт задачи {$message->getRoutingKey()}");
                foreach ($this->getDependencies() as $dependency) {
                    sleep(1);
                    $this->consumer->sortExchanges()[$dependency]->run();
                }
                $this->runEnvelope($message);
                $this->queue->ack($message->getDeliveryTag());
                $this->consumer->getLogger()->notice("Финиш задачи {$message->getRoutingKey()} обработана");
            } catch (\Exception $e) {
                $this->queue->nack($message->getDeliveryTag());
                $error = "Ошибка обработки задачи {$message->getRoutingKey()}: {$e->getMessage()}\n" . $e->getTraceAsString();
                $this->consumer->getLogger()->error($error);
            }
        }
    }

    /**
     * @param $message
     * @param $key
     * @param int $flags
     * @param array $attributes
     * @return bool
     */
    public function publish($message, $key, $flags = AMQP_NOPARAM, $attributes = [])
    {
        $attributes = array_merge([
            "delivery_mode" => 2,
            "timestamp" => time()
        ], $attributes);
        return $this->exchange->publish($message, $this->getPublishKey($key), $flags, $attributes);
    }

    /**
     * Получить главный класс "Потребителя"
     * @return Consumer
     */
    public function getConsumer()
    {
        return $this->consumer;
    }

    /**
     * Получить точку обмена AMQP
     * @return \AMQPExchange
     */
    public function getExchange()
    {
        return $this->exchange;
    }

    /**
     * Получить очередь AMQP
     * @return \AMQPQueue
     */
    public function getQueue()
    {
        return $this->queue;
    }
    
}
