<?php
/**
 * By: Ivan Kiselev aka shaqito[at]gmail.com
 * Via: PhpStorm.
 * At: 03.08.2016 22:40
 */

namespace Hogart\Lk\Exchange\SOAP\Method;

use Bitrix\Main\Type\Date;
use Bitrix\Main\Type\DateTime;
use Hogart\Lk\Entity\OrderPaymentTable;
use Hogart\Lk\Entity\OrderTable;
use Hogart\Lk\Exchange\SOAP\AbstractMethod;
use Bitrix\Main\Entity\UpdateResult;
use Hogart\Lk\Exchange\SOAP\MethodException;
use Hogart\Lk\Exchange\SOAP\Request;
use Hogart\Lk\Exchange\SOAP\Response;
use Hogart\Lk\Exchange\SOAP\ResponseObject;

class Payment extends AbstractMethod
{
    /**
     * {@inheritDoc}
     */
    function getName()
    {
        return "Payment";
    }

    public function paymentsGet()
    {
        return $this->client->getSoapClient()->PaymentsGet(new Request());
    }

    public function paymentsAnswer(Response $response)
    {
        if (count($response->Response) && $this->is_answer) {
            return $this->client->getSoapClient()->PaymentsAnswer($response);
        }
    }

    /**
     * @return int
     * @throws \Bitrix\Main\ArgumentException
     */
    public function updatePayments()
    {
        $answer = new Response();
        $response = $this->paymentsGet();
        foreach ($response->return->Payment as $order_payment) {
            $order = OrderTable::getList([
                'filter'=>[
                    '=guid_id'=>$order_payment->Order_ID
                ]
            ])->fetch();

            if (empty($order['id'])) {
                $answer->addResponse(new ResponseObject($order_payment->Payment_ID, new MethodException(MethodException::ERROR_NO_ORDER, [$order_payment->Order_ID])));
                continue;
            }

            // данные по Расчетному счету
            $result = OrderPaymentTable::createOrUpdateByField([
                'guid_id' => $order_payment->Payment_ID,
                'order_id' => $order['id'],
                'payment_date' => new DateTime($order_payment->Payment_Date, 'Y-m-d H:i:s'),
                'number' => (string)$order_payment->Payment_Number,
                'form' => intval($order_payment->Form),
                'direction' => intval($order_payment->Moving_Direction),
                'total' => floatval($order_payment->Sum),
                'currency_code' => $order_payment->Payment_ID_Money,
                'is_active' => !$order_payment->deletion_mark
            ], 'guid_id');

            if ($result->getErrorCollection()->count()) {
                $error = $result->getErrorCollection()->current();
                $answer->addResponse(new ResponseObject($order_payment->Payment_ID, new MethodException(MethodException::ERROR_BITRIX, [$error->getMessage(), $error->getCode()])));
            } else {
                if ($result->getId()) {
                    if ($result instanceof UpdateResult) {
                        $this->client->getLogger()->notice("Обновлена запись Оплаты заказа {$result->getId()} ({$order_payment->Payment_ID})");
                    } else {
                        $this->client->getLogger()->notice("Добавлена запись Оплаты заказа {$result->getId()} ({$order_payment->Payment_ID})");
                    }
                    $answer->addResponse(new ResponseObject($order_payment->Payment_ID));
                } else {
                    $answer->addResponse(new ResponseObject($order_payment->Payment_ID, new MethodException(MethodException::ERROR_UNDEFINED)));
                }
            }
        }
        $this->paymentsAnswer($answer);
        return count($answer->Response);
    }

}