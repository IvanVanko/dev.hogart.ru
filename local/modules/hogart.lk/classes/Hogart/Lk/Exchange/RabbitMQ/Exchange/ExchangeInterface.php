<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 30/07/16
 * Time: 22:11
 */

namespace Hogart\Lk\Exchange\RabbitMQ\Exchange;


use Hogart\Lk\Exchange\RabbitMQ\Consumer;

interface ExchangeInterface
{
    /**
     * @return array
     */
    function getDependencies();
    /**
     * @param Consumer $consumer
     * @return ExchangeInterface
     */
    function useConsumer(Consumer $consumer);

    /**
     * @return string
     */
    function getExchangeName();

    /**
     * @return string
     */
    function getQueueName();

    /**
     * @return void
     */
    function run();

    /**
     * @param \AMQPEnvelope $envelope
     * @return mixed
     */
    function runEnvelope(\AMQPEnvelope $envelope);
}