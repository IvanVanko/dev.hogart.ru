<?php
/**
 * By: Ivan Kiselev aka shaqito[at]gmail.com
 * Via: PhpStorm.
 * At: 03.08.2016 16:36
 */

namespace Hogart\Lk\Exchange\SOAP\Method\OrderDocs;
use Hogart\Lk\Entity\OrderTable;
use Hogart\Lk\Entity\RTUItemTable;
use Hogart\Lk\Exchange\SOAP\AbstractMethod;
use Hogart\Lk\Entity\RTUTable;
use Bitrix\Main\Type\Date;
use Bitrix\Main\Entity\UpdateResult;
use Hogart\Lk\Exchange\SOAP\Method\MethodException;
use Hogart\Lk\Exchange\SOAP\Method\Response;
use Hogart\Lk\Exchange\SOAP\Method\ResponseObject;

class RTU extends AbstractMethod
{
    const ERROR_NO_ORDER = 1;

    protected static $errors = [
        self::ERROR_NO_ORDER => "Не найден Заказ %s",
    ];

    /**
     * @inheritDoc
     */
    function getName()
    {
        return "RTU";
    }

    /**
     * @todo Доработать после появления метода Docs_Order
     * 
     * @param $rtus
     * @param Response $answer
     * @return int
     * @throws \Bitrix\Main\ArgumentException
     */
    public function updateRTUs($rtus, Response $answer)
    {
        foreach ($rtus as $rtu) {
            $order = OrderTable::getList([
                'filter'=>[
                    '=guid_id'=>$rtu->Order_ID
                ]
            ])->fetch();

            if (empty($order['id'])) {
                $answer->addResponse(new ResponseObject($rtu->RTU_ID, new MethodException($this->getError(self::ERROR_NO_ORDER, [$rtu->Order_ID]))));
                continue;
            }
            $result = RTUTable::createOrUpdateByField([
                'guid_id' => $rtu->RTU_ID,
                'order_id' => $order['id'],
                'store_guid' => $rtu->warehouse_ID,
                'number' => $rtu->RTU_Number,
                'rtu_date' => new Date((string)$rtu->RTU_Date, 'Y-m-d'),
                'currency_code' => $rtu->RTU_ID_Money,
                'order_type' => $rtu->Delivery,
                'is_active' => !$rtu->deletion_mark,
            ], 'guid_id');

            if ($result->getErrorCollection()->count()) {
                $error = $result->getErrorCollection()->current();
                $answer->addResponse(new ResponseObject($rtu->RTU_ID, new MethodException($error->getMessage(), intval($error->getCode()))));
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
                    $answer->addResponse(new ResponseObject($rtu->RTU_ID, new MethodException(self::$default_errors[self::ERROR_UNDEFINED], self::ERROR_UNDEFINED)));
                }
            }
        }
        return count($answer->Response);
    }

}