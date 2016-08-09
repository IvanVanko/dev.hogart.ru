<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 31/07/16
 * Time: 00:45
 */

namespace Hogart\Lk\Exchange\RabbitMQ\Exchange;


use Hogart\Lk\Exchange\SOAP\Client;

/**
 * Задачи RabbitMQ - Аккаунты
 * 
 * @rabbitmq.exchange
 * | *__Код задачи__*           | *__Тело сообщения__* | *__Описание__*                                         |
 * |:----------:                |:----------:           |--------------                                          |
 * | __account.get__            |                       | _Задача получения Аккаунтов из КИС_                   |
 * | __account.send_password__  | _account_login_       | _Задача отправки ссылки клиенту для смены пароля_     |
 *
 * @package Hogart\Lk\Exchange\RabbitMQ\Exchange
 */
class AccountExchange extends AbstractExchange
{
    /**
     * @inheritDoc
     */
    public function getDependencies()
    {
        return [
            __NAMESPACE__ . '\StaffExchange',
            __NAMESPACE__ . '\CompanyExchange',
        ];
    }

    /**
     * @inheritDoc
     */
    function getQueueName()
    {
        return "account";
    }

    /**
     * @inheritDoc
     */
    function runEnvelope(\AMQPEnvelope $envelope)
    {
        switch ($key = $this->getRoutingKey($envelope)) {
            case 'get':
                // синхронизация с 1С
                $count = Client::getInstance()->Account->updateAccounts();
                if (!empty($count)) {
                    $this
                        ->exchange
                        ->publish("", $this->getPublishKey($key), AMQP_NOPARAM, ["delivery_mode" => 2]);
                }
                break;
            case 'send_password':
                // смена пароля для нового пользователя ЛК
                \CUser::SendPassword($envelope->getBody(), $envelope->getBody());
                break;
        }
    }

}