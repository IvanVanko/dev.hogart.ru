<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 30/07/16
 * Time: 21:40
 */

namespace Hogart\Lk\Exchange\RabbitMQ;


use Hogart\Lk\Exchange\RabbitMQ\Exchange\ExchangeInterface;
use Hogart\Lk\Exchange\RabbitMQ\Logger\BitrixLogger;
use Hogart\Lk\Exchange\RabbitMQ\Logger\LoggerCollection;
use Hogart\Lk\Exchange\RabbitMQ\Logger\LoggerInterface;

class Producer
{
    /** @var \AMQPConnection  */
    protected $connection;
    /** @var \AMQPChannel  */
    protected $channel;
    /** @var  ExchangeInterface[] */
    protected $exchanges = [];
    /** @var LoggerCollection  */
    protected $logger;

    /**
     * Producer constructor.
     *
     * @param string $host
     * @param int $port
     * @param string $login
     * @param string $password
     */
    public function __construct($host = 'localhost', $port = 5672, $login = 'guest', $password = 'guest')
    {
        $this->connection = new \AMQPConnection([
            'host' => $host,
            'port' => $port,
            'vhost' => '/',
            'login' => $login,
            'password' => $password
        ]);
        $this->connection->connect();
        $this->channel = new \AMQPChannel($this->connection);
        $this->logger = new LoggerCollection(new BitrixLogger());
    }

    /**
     * @param LoggerInterface $logger
     * @return $this
     */
    public function registerLogger(LoggerInterface $logger)
    {
        $this->logger->registerLogger($logger);

        return $this;
    }

    /**
     * @param LoggerInterface $logger
     * @return $this
     */
    public function unregisterLogger(LoggerInterface $logger)
    {
        $this->logger->unregisterLogger($logger);

        return $this;
    }

    /**
     * @return LoggerCollection
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param LoggerInterface $logger
     * @return $this
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * @return \AMQPConnection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @return \AMQPChannel
     */
    public function getChannel()
    {
        return $this->channel;
    }



    /**
     * @param ExchangeInterface|ExchangeInterface[] $exchangeInterface
     * @return $this
     */
    public function registerExchange($exchangeInterface)
    {
        if (!is_array($exchangeInterface)) $exchangeInterface = (array)$exchangeInterface;

        foreach ($exchangeInterface as $exchange) {
            $this->exchanges[] = $exchange->useProducer($this);
        }
        usort($this->exchanges, function ($a, $b) { return $a->getPriority() < $b->getPriority(); });
        
        return $this;
    }

    /**
     * @throws Exception
     */
    public function run()
    {
        if (empty($this->exchanges)) throw new Exception("No exchanges registered");

        foreach ($this->exchanges as $exchange) {
            $exchange->run();
        }
    }
}
