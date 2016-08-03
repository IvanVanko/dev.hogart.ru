<?php
/**
 * By: Ivan Kiselev aka shaqito[at]gmail.com
 * Via: PhpStorm.
 * At: 03.08.2016 16:36
 */

namespace Hogart\Lk\Exchange\SOAP\Method;
use Hogart\Lk\Entity\OrderTable;
use Hogart\Lk\Exchange\SOAP\AbstractMethod;
use Hogart\Lk\Entity\RTUTable;
use Bitrix\Main\Type\Date;
use Bitrix\Main\Entity\UpdateResult;

class RTU extends AbstractMethod
{
    /**
     * @inheritDoc
     */
    function getName()
    {
        return "RTU";
    }

    public function getRTUs()
    {
        return $this->client->getSoapClient()->RTUsGet(new Request());
    }

    public function RTUAnswer(Response $response)
    {
        if (count($response->Response) && $this->is_answer) {
            return $this->client->getSoapClient()->RTUAnswer($response);
        }
    }

    public function updatePaymentAccounts()
    {
        $answer = new Response();
        $response = $this->getRTUs();
        foreach ($response->return->RTU as $rtu) {
            $order = OrderTable::getList([
                'filter'=>[
                    '=guid_id'=>$rtu->Order_ID
                ]
            ])->fetch();
            $result = RTUTable::createOrUpdateByField([
                'guid_id' => $rtu->RTU_ID,
                'order_id' => $order['id'],
                'store_guid' => $rtu->Warehouse_ID,
                'number' => $rtu->RTU_Number,
                'rtu_date' => new Date((string)$rtu->RTU_Date, 'Y-m-d'),
                'currency_code' => $rtu->RTU_ID_Money,
                'order_type' => $rtu->Delivery,
                'is_active' => !$rtu->deletion_mark,
            ], 'guid_id');

            if ($result->getErrorCollection()->count()) {
                $error = $result->getErrorCollection()->current();
                $answer->addResponse(new ResponseObject($rtu->RTU_ID, new MethodException($error->getMessage(), intval($error->getCode()))));
                $this->client->getLogger()->error($error->getMessage() . " (" . $error->getCode() . ")");
            } else {
                if ($result->getId()) {
                    if ($result instanceof UpdateResult) {
                        $this->client->getLogger()->notice("Обновлена запись Отгрузки {$result->getId()} ({$rtu->RTU_ID})");
                    } else {
                        $this->client->getLogger()->notice("Добавлена запись Отгрузки {$result->getId()} ({$rtu->RTU_ID})");
                    }
                    $answer->addResponse(new ResponseObject($rtu->RTU_ID));
                } else {
                    $answer->addResponse(new ResponseObject($rtu->RTU_ID, new MethodException(self::$default_errors[self::ERROR_UNDEFINED], self::ERROR_UNDEFINED)));
                    $this->client->getLogger()->error(self::$default_errors[self::ERROR_UNDEFINED] . " (" . self::ERROR_UNDEFINED . ")");
                }
            }
        }
        $this->RTUAnswer($answer);
        return count($answer->Response);
    }

}