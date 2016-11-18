<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 04/08/16
 * Time: 01:32
 */

namespace Hogart\Lk\Exchange\SOAP\Method;

use Hogart\Lk\Entity\FlashMessagesTable;
use Hogart\Lk\Entity\OrderTable;
use Hogart\Lk\Exchange\SOAP\AbstractMethod;
use Hogart\Lk\Exchange\SOAP\AbstractPutRequest;
use Hogart\Lk\Exchange\SOAP\MethodException;
use Hogart\Lk\Exchange\SOAP\Request;
use Hogart\Lk\Exchange\SOAP\Response;
use Hogart\Lk\Helper\Template\Message;

class Orders extends AbstractMethod
{
    /**
     * {@inheritDoc}
     */
    function getName()
    {
        return "Orders";
    }

    public function ordersPut(AbstractPutRequest $request)
    {
        $this->client->getLogger()->debug(var_export($request->__toRequest(), true));
        $response = $this->client->getSoapClient()->OrdersPut($request->__toRequest());
        $this->client->getLogger()->debug(var_export($response, true));
        if (!empty($response->return->Error)) {
            $error = new MethodException(MethodException::ERROR_SOAP, [$response->return->ErrorText, $response->return->Error]);
            $this->client->getLogger()->error($error->getMessage());
            throw $error;
        }
        foreach ($response->return->Response as $order) {
            OrderTable::update($order->ID_Site, [
                'guid_id' => $order->ID
            ]);

            $order_row = OrderTable::getRowById($order->ID_Site);
            $message = new Message(
                OrderTable::showName($order_row) . " обновлен!",
                Message::SEVERITY_INFO
            );
            $message
                ->setIcon('fa fa-file-text-o')
                ->setUrl("/account/order/" . $order->ID_Site)
                ->setDelay(0)
            ;
            FlashMessagesTable::addNewMessage($order_row['account_id'], $message);

        }
        return $response;
    }

    /**
     * Получение Заказов от КИС
     * @return mixed
     */
    public function ordersGet()
    {
        return $this->client->getSoapClient()->OrdersGet(new Request());
    }

    /**
     * Ответ в КИС о полученных Заказах
     * @param Response $response
     * @return mixed
     */
    public function ordersAnswer(Response $response)
    {
        if (count($response->Response) && $this->is_answer) {
            return $this->client->getSoapClient()->OrdersAnswer($response);
        }
    }

    /**
     * Обработка полученных от КИС Заказов
     * @return int
     */
    public function updateOrders()
    {
        $answer = new Response();
        $response = $this->ordersGet();
        $this->client->Order->updateOrders($response->return->Order, $answer);
        $this->ordersAnswer($answer);
        return count($answer->Response);
    }
}