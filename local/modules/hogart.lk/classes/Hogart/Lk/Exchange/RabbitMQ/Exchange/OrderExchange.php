<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 04/08/16
 * Time: 01:57
 */

namespace Hogart\Lk\Exchange\RabbitMQ\Exchange;


use Hogart\Lk\Entity\AccountTable;
use Hogart\Lk\Entity\FlashMessagesTable;
use Hogart\Lk\Entity\OrderTable;
use Hogart\Lk\Entity\StaffTable;
use Hogart\Lk\Exchange\SOAP\AbstractPutRequest;
use Hogart\Lk\Exchange\SOAP\Client;
use Hogart\Lk\Exchange\SOAP\Request\Order;
use Hogart\Lk\Helper\Template\Message;

/**
 * Задачи RabbitMQ - Заказы
 *
 * @rabbitmq.exchange
 * | *__Код задачи__*           | *__Тело сообщения__* | *__Описание__*                    |
 * |:----------:                |:----------:          |--------------                     |
 * | __order.get__              |                      | _Задача получения Заказов из КИС_ |
 *
 * @package Hogart\Lk\Exchange\RabbitMQ\Exchange
 */
class OrderExchange extends AbstractExchange
{
    /**
     * {@inheritDoc}
     */
    public function getDependencies()
    {
        return [
            __NAMESPACE__ . '\HogartCompanyExchange',
            __NAMESPACE__ . '\CompanyExchange',
            __NAMESPACE__ . '\AccountExchange',
            __NAMESPACE__ . '\StaffExchange'
        ];
    }

    /**
     * {@inheritDoc}
     */
    function getQueueName()
    {
        return "order";
    }

    /**
     * {@inheritDoc}
     */
    function runEnvelope(\AMQPEnvelope $envelope)
    {
        switch ($key = $this->getRoutingKey($envelope)) {
            case 'get':
                Client::getInstance()->Orders->updateOrders();
                break;
            case 'put':
                $request = unserialize($envelope->getBody());
                if ($request instanceof AbstractPutRequest) {
                    Client::getInstance()->Orders->ordersPut($request);
                }
                break;
            case 'request':
                $this->getConsumer()->getLogger()->notice(vsprintf("Попытка запроса заказа %s из 1с", [$envelope->getBody()]));
                $order = OrderTable::getOrder(intval($envelope->getBody()));
                if (!empty($order)) {
                    Client::getInstance()->Orders->ordersPut(new Order([$order]));
                }
                break;
            case 'unblock':
                $data = simplexml_load_string($envelope->getBody());

                $order = OrderTable::getByField("guid_id", $data->Order_ID);
                $account = AccountTable::getByField("user_guid_id", $data->Acc_ID);
                $staff = StaffTable::getByField("guid_id", $data->Staf_ID);

                $this->getConsumer()->getLogger()->notice(vsprintf("Попытка разблокировки заказа %s из 1с", [$order['id']]));

                Client::getInstance()->Order->unblockOrder($data->Order_ID);

                if (empty($account)) break;

                $message = new Message(
                    vsprintf(
                        "менеджер %s разблокировал %s по причине %s",
                        [
                            OrderTable::showName($order),
                            implode(' ', [$staff['last_name'], $staff['name']]),
                            $data->Reason
                        ]
                    ),
                    Message::SEVERITY_WARNING
                );
                $message
                    ->setIcon('fa fa-exclamation-triangle')
                ;

                $flash_result = FlashMessagesTable::addNewMessage($account['id'], $message);
                if ($flash_result->getId()) {
                    $this->getConsumer()->getLogger()->notice("Добавлено оповещение по разблокировке заказа ({$order['id']})");
                }

                break;
        }
    }

}