<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 30/07/16
 * Time: 22:11
 */

namespace Hogart\Lk\Exchange\RabbitMQ\Exchange;


use Hogart\Lk\Exchange\RabbitMQ\Consumer;

/**
 * Интерфейс Задачи RabbitMQ
 * @package Hogart\Lk\Exchange\RabbitMQ\Exchange
 */
interface ExchangeInterface
{
    /**
     * Получение зависимостей задачи
     * 
     * Данный метод должен вернуть массив имен классов, от которых зависит данный обмен
     * 
     * @return array
     */
    function getDependencies();
    /**
     * Использовать главный класс "Потребителя"
     * @param Consumer $consumer
     * @return ExchangeInterface
     */
    function useConsumer(Consumer $consumer);

    /**
     * Получить имя точки обмена
     * @return string
     */
    function getExchangeName();

    /**
     * Получить имя очереди
     * @return string
     */
    function getQueueName();

    /**
     * Запуск задач обмена
     * @return void
     */
    function run();

    /**
     * Обработать сообщение
     * @param \AMQPEnvelope $envelope
     * @return mixed
     */
    function runEnvelope(\AMQPEnvelope $envelope);
}