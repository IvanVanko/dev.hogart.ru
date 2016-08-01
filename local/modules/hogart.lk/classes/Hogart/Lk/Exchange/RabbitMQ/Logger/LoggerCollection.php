<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 31/07/16
 * Time: 03:43
 */

namespace Hogart\Lk\Exchange\RabbitMQ\Logger;


class LoggerCollection implements LoggerInterface
{
    /** @var  LoggerInterface[] */
    protected $loggers;

    /**
     * LoggerCollection constructor.
     * @param LoggerInterface|LoggerInterface[] $loggers
     */
    public function __construct($loggers = [])
    {
        if (is_object($loggers)) $loggers = [$loggers];
        foreach ($loggers as $logger) {
            $this->registerLogger($logger);
        }
    }

    /**
     * @param LoggerInterface $logger
     * @return $this
     */
    public function registerLogger(LoggerInterface $logger)
    {
        $this->loggers[get_class($logger)] = $logger;
        
        return $this;
    }

    /**
     * @param LoggerInterface $logger
     * @return $this
     */
    public function unregisterLogger(LoggerInterface $logger)
    {
        unset($this->loggers[get_class($logger)]);
        
        return $this;
    }

    function error($message)
    {
        foreach ($this->loggers as $logger) {
            $logger->error($message);
        }
    }

    function warning($message)
    {
        foreach ($this->loggers as $logger) {
            $logger->warning($message);
        }
    }

    function notice($message)
    {
        foreach ($this->loggers as $logger) {
            $logger->notice($message);
        }
    }

    function debug($message)
    {
        foreach ($this->loggers as $logger) {
            $logger->debug($message);
        }
    }

}