<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 30/07/16
 * Time: 21:40
 */

namespace Hogart\Lk\Exchange\RabbitMQ;


use Hogart\Lk\Creational\Singleton;
use Hogart\Lk\Exchange\RabbitMQ\Exchange\ExchangeInterface;
use Hogart\Lk\Logger\BitrixLogger;
use Hogart\Lk\Logger\LoggerCollection;
use Hogart\Lk\Logger\LoggerInterface;
use MJS\TopSort\Implementations\StringSort;

/**
 * Класс главного "Потребителя"
 * @package Hogart\Lk\Exchange\RabbitMQ
 */
class Consumer
{
    use Singleton;

    /** @var \AMQPConnection  */
    protected $connection;
    /** @var \AMQPChannel  */
    protected $channel;
    /** @var  ExchangeInterface[] */
    protected $exchanges = [];
    /** @var LoggerCollection  */
    protected $logger;
    /** @var bool  */
    protected $is_cli_context = false;
    /** @var bool  */
    protected $is_sorted = false;

    /**
     * Consumer constructor.
     */
    protected function create()
    {
        $this->connection = new \AMQPConnection([
            'host' => \COption::GetOptionString("hogart.lk", "RABBITMQ_HOST"),
            'port' => \COption::GetOptionString("hogart.lk", "RABBITMQ_PORT"),
            'vhost' => \COption::GetOptionString("hogart.lk", "RABBITMQ_VHOST"),
            'login' => \COption::GetOptionString("hogart.lk", "RABBITMQ_LOGIN"),
            'password' => \COption::GetOptionString("hogart.lk", "RABBITMQ_PASSWORD")
        ]);
        $this->connection->connect();
        $this->channel = new \AMQPChannel($this->connection);
        $this->logger = new LoggerCollection("RABBITMQ", new BitrixLogger());
    }

    /**
     * Зарегистрировать логгер
     * @param LoggerInterface $logger
     * @return $this
     */
    public function registerLogger(LoggerInterface $logger)
    {
        $this->logger->registerLogger($logger);

        return $this;
    }

    /**
     * Удалить с регистрации логгер
     * @param LoggerInterface $logger
     * @return $this
     */
    public function unregisterLogger(LoggerInterface $logger)
    {
        $this->logger->unregisterLogger($logger);

        return $this;
    }

    /**
     * Получить логгеры
     * @return LoggerCollection
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * Установить логгер
     * @param LoggerInterface $logger
     * @return $this
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * Получить соединение AMQP c RabbitMQ
     * @return \AMQPConnection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Получить канал AMQP RabbitMQ
     * @return \AMQPChannel
     */
    public function getChannel()
    {
        return $this->channel;
    }


    /**
     * Зарегистрировать обмен (Задача RabbitMQ)
     * @param ExchangeInterface|ExchangeInterface[] $exchangeInterface
     * @return $this
     */
    public function registerExchange($exchangeInterface)
    {
        if (is_object($exchangeInterface)) $exchangeInterface = [$exchangeInterface];
        foreach ($exchangeInterface as $exchange) {
            $this->exchanges[get_class($exchange)] = $exchange->useConsumer($this);
        }
        return $this;
    }

    /**
     * Топологическая сортировка обменов по зависимостям
     * @return array|Exchange\ExchangeInterface[]
     */
    public function sortExchanges()
    {
        if (!$this->is_sorted) {
            $sorter = new StringSort();
            foreach ($this->exchanges as $exchange) {
                $sorter->add(get_class($exchange), $exchange->getDependencies());
            }
            $dependencies = array_values($sorter->sort());
            $sort = [];
            foreach ($dependencies as $dependency) {
                $sort[$dependency] = $this->exchanges[$dependency];
            }
            $this->exchanges = $sort;
            $this->is_sorted = true;
        }
        return $this->exchanges;
    }

    /**
     * @return boolean
     */
    public function isIsCliContext()
    {
        return $this->is_cli_context;
    }

    /**
     * @param boolean $is_cli_context
     * @return $this
     */
    public function setIsCliContext($is_cli_context)
    {
        $this->is_cli_context = $is_cli_context;
        return $this;
    }

    /**
     * Запуск "Потребителя"
     * @throws Exception
     */
    public function run()
    {
        if (empty($this->exchanges)) throw new Exception("No exchanges registered");

        foreach ($this->sortExchanges() as $exchange) {
            sleep(1);
            $exchange->run();
        }
    }
}
