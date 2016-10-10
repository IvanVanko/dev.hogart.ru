<?php
/**
 * By: Ivan Kiselev aka shaqito[at]gmail.com
 * Via: PhpStorm.
 * At: 03.08.2016 16:36
 */

namespace Hogart\Lk\Exchange\SOAP\Method;

use Bitrix\Main\Type\DateTime;
use Hogart\Lk\Entity\OrderTable;
use Hogart\Lk\Entity\RTUItemTable;
use Hogart\Lk\Exchange\SOAP\AbstractMethod;
use Hogart\Lk\Entity\RTUTable;
use Bitrix\Main\Type\Date;
use Bitrix\Main\Entity\UpdateResult;
use Hogart\Lk\Exchange\SOAP\MethodException;
use Hogart\Lk\Exchange\SOAP\Request;
use Hogart\Lk\Exchange\SOAP\Response;
use Hogart\Lk\Exchange\SOAP\ResponseObject;

/**
 * Обмен с КИС - Реализация товаров и услуг (Отгрузка)
 * @package Hogart\Lk\Exchange\SOAP\Method\OrderDocs
 */
class RTU extends AbstractMethod
{
    /**
     * {@inheritDoc}
     */
    function getName()
    {
        return "RTU";
    }

    public function rtuGet()
    {
        return $this->client->getSoapClient()->RTUGet(new Request());
    }

    public function rtuAnswer(Response $response)
    {
        if (count($response->Response) && $this->is_answer) {
            return $this->client->getSoapClient()->RTUAnswer($response);
        }
    }

    /**
     * @return int
     * @throws \Bitrix\Main\ArgumentException
     */
    public function updateRTUs()
    {
        $answer = new Response();
        $response = $this->rtuGet();

        foreach ($response->return->RTU as $rtu) {
            $order = OrderTable::getList([
                'filter'=>[
                    '=guid_id'=>$rtu->Order_ID
                ]
            ])->fetch();

            if (empty($order['id'])) {
                $answer->addResponse(new ResponseObject($rtu->RTU_ID, new MethodException(MethodException::ERROR_NO_ORDER, [$rtu->Order_ID])));
                continue;
            }
            $result = RTUTable::createOrUpdateByField([
                'guid_id' => $rtu->RTU_ID,
                'order_id' => $order['id'],
                'number' => $rtu->RTU_Number,
                'rtu_date' => new DateTime((string)$rtu->RTU_Date, 'Y-m-d H:i:s'),
                'currency_code' => $rtu->RTU_ID_Money,
                'is_active' => !$rtu->deletion_mark,
            ], 'guid_id');

            if ($result->getErrorCollection()->count()) {
                $error = $result->getErrorCollection()->current();
                $answer->addResponse(new ResponseObject($rtu->RTU_ID, new MethodException(MethodException::ERROR_BITRIX, [$error->getMessage(), $error->getCode()])));
            } else {
                if ($result->getId()) {
                    if ($result instanceof UpdateResult) {
                        $this->client->getLogger()->notice("Обновлена запись Отгрузки {$result->getId()} ({$rtu->RTU_ID})");
                    } else {
                        $this->client->getLogger()->notice("Добавлена запись Отгрузки {$result->getId()} ({$rtu->RTU_ID})");
                    }
                    $answer->addResponse($response = new ResponseObject($rtu->RTU_ID));

                    try {
                        $this->client->RTUItem->updateRTUItems($rtu->RTU_Items);
                    } catch (MethodException $e) {
                        $response->setError($e);
                        RTUItemTable::deleteByRTUId($result->getId());
                        RTUTable::delete($result->getId());
                    }
                } else {
                    $answer->addResponse(new ResponseObject($rtu->RTU_ID, new MethodException(MethodException::ERROR_UNDEFINED)));
                }
            }
        }
        $this->rtuAnswer($answer);
        return count($answer->Response);
    }

}