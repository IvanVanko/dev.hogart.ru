<?php
/**
 * By: Ivan Kiselev aka shaqito[at]gmail.com
 * Via: PhpStorm.
 * At: 03.08.2016 22:40
 */

namespace Hogart\Lk\Exchange\SOAP\Method;
use Hogart\Lk\Entity\OrderPaymentTable;
use Hogart\Lk\Entity\OrderTable;
use Hogart\Lk\Exchange\SOAP\AbstractMethod;
use Bitrix\Main\Entity\UpdateResult;

class OrderPayment extends AbstractMethod
{
    /**
     * @inheritDoc
     */
    function getName()
    {
        return "OrderPayment";
    }

    public function getOrderPayments()
    {
        return $this->client->getSoapClient()->OrderPaymentsGet(new Request());
    }

    public function orderPaymentAnswer(Response $response)
    {
        if (count($response->Response) && $this->is_answer) {
            return $this->client->getSoapClient()->OrderPaymentAnswer($response);
        }
    }

    public function updateOrderPayments()
    {
        $answer = new Response();
        $response = $this->getOrderPayments();

        foreach ($response->return->Payment as $order_payment) {
            $order = OrderTable::getList([
                'filter'=>[
                    '=guid_id'=>$order_payment->Order_ID
                ]
            ])->fetch();

            // данные по Расчетному счету
            $result = OrderPaymentTable::createOrUpdateByField([
                'guid_id' => $order_payment->Payment_ID,
                'order_id' => $order['id'],
                'payment_date' => $order_payment->Payment_Date,
                'number' => $order_payment->Payment_Number,
                'form' => $order_payment->Form,
                'direction' => $order_payment->Moving_Direction,
                'total' => $order_payment->Sum,
                'currency_code' => $order_payment->Payment_ID_Money,
                'is_active' => !$order_payment->deletion_mark
            ], 'guid_id');

            if ($result->getErrorCollection()->count()) {
                $error = $result->getErrorCollection()->current();
                $answer->addResponse(new ResponseObject($order_payment->Payment_ID, new MethodException($error->getMessage(), intval($error->getCode()))));
                $this->client->getLogger()->error($error->getMessage() . " (" . $error->getCode() . ")");
            } else {
                if ($result->getId()) {
                    if ($result instanceof UpdateResult) {
                        $this->client->getLogger()->notice("Обновлена запись Оплаты заказа {$result->getId()} ({$order_payment->Payment_ID})");
                    } else {
                        $this->client->getLogger()->notice("Добавлена запись Оплаты заказа {$result->getId()} ({$order_payment->Payment_ID})");
                    }
                    $answer->addResponse(new ResponseObject($order_payment->Payment_ID));
                } else {
                    $answer->addResponse(new ResponseObject($order_payment->Payment_ID, new MethodException(self::$default_errors[self::ERROR_UNDEFINED], self::ERROR_UNDEFINED)));
                    $this->client->getLogger()->error(self::$default_errors[self::ERROR_UNDEFINED] . " (" . self::ERROR_UNDEFINED . ")");
                }
            }
        }
        $this->orderPaymentAnswer($answer);
        return count($answer->Response);
    }

}