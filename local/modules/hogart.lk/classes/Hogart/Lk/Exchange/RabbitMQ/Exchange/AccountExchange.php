<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 31/07/16
 * Time: 00:45
 */

namespace Hogart\Lk\Exchange\RabbitMQ\Exchange;


use Hogart\Lk\Entity\AccountTable;
use Hogart\Lk\Exchange\SOAP\Client;

/**
 * Задачи RabbitMQ - Аккаунты
 * 
 * @rabbitmq.exchange
 * | *__Код задачи__*           | *__Тело сообщения__* | *__Описание__*                                         |
 * |:----------:                |:----------:          |--------------                                          |
 * | __account.get__            |                      | _Задача получения Аккаунтов из КИС_                   |
 * | __account.send_password__  | _account_login_      | _Задача отправки ссылки клиенту для смены пароля_     |
 *
 * @package Hogart\Lk\Exchange\RabbitMQ\Exchange
 */
class AccountExchange extends AbstractExchange
{
    /**
     * {@inheritDoc}
     */
    public function getDependencies()
    {
        return [
            __NAMESPACE__ . '\ContactExchange',
            __NAMESPACE__ . '\ContactInfoExchange',
            __NAMESPACE__ . '\CompanyExchange',
            __NAMESPACE__ . '\StaffExchange',
        ];
    }

    /**
     * {@inheritDoc}
     */
    function getQueueName()
    {
        return "account";
    }

    /**
     * {@inheritDoc}
     */
    function runEnvelope(\AMQPEnvelope $envelope)
    {
        switch ($key = $this->getRoutingKey($envelope)) {
            case 'get':
                // синхронизация с 1С
                Client::getInstance()->Account->updateAccounts();
                break;
            case 'send_password':
                // отсылка письма для нового пользователя ЛК
                AccountTable::sendNewAccountPassword($envelope->getBody());
                break;
        }
    }

}