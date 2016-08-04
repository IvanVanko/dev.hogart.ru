<?php
/**
 * By: Ivan Kiselev aka shaqito[at]gmail.com
 * Via: PhpStorm.
 * At: 03.08.2016 16:36
 */

namespace Hogart\Lk\Exchange\SOAP\Method\OrderDocs;
use Bitrix\Iblock\ElementTable;
use Hogart\Lk\Exchange\SOAP\AbstractMethod;
use Hogart\Lk\Entity\RTUItemTable;
use Hogart\Lk\Entity\RTUTable;
use Bitrix\Main\Type\Date;
use Bitrix\Main\Entity\UpdateResult;
use Bitrix\Main\DB\SqlExpression;
use Hogart\Lk\Exchange\SOAP\Method\MethodException;
use Hogart\Lk\Exchange\SOAP\Method\Response;
use Hogart\Lk\Exchange\SOAP\Method\ResponseObject;

class RTUItem extends AbstractMethod
{
    /**
     * @inheritDoc
     */
    function getName()
    {
        return "RTUItem";
    }

    /**
     * @todo Доработать после появления метода Docs_Order
     * 
     * @param $rtu_items
     * @param Response $answer
     * @return int
     * @throws \Bitrix\Main\ArgumentException
     */
    public function updateRTUItems($rtu_items, Response $answer)
    {
        foreach ($rtu_items as $rtu_item) {
            $rtu = RTUTable::getList([
                'filter'=>[
                    '=guid_id'=>$rtu_item->RTU_ID
                ]
            ])->fetch();

            $order_item = ElementTable::getList([
                'filter'=>[
                    '=XML_ID' => $rtu_item->ID_Item,
                    '=ref.IBLOCK_ID' => new SqlExpression('?i', CATALOG_IBLOCK_ID)
                ]
            ])->fetch();
            // данные по Элементам Платежных документов на отгрузку
            $result = RTUItemTable::createOrUpdateByField([
                'guid_id' => $rtu_item->RTU_Item_ID, // @todo такого поля с guid'ом нету - проверить что бы добавили - или возможно делать вместе с RTU
                'rtu_id' => $rtu['id'],
                'item_id' => $order_item['ID'],
                'count' => $rtu_item->Count,
                'cost' => $rtu_item->Cost,
                'discount' => $rtu_item->Discount,
                'discount_cost' => $rtu_item->Cost_Disc,
                'total' => $rtu_item->Summ,
                'total_vat' => $rtu_item->Sum_VAL,
                'group' => $rtu_item->Group,
                'shipping_date' => new Date((string)$rtu_item->Ship_Date, 'Y-m-d'),
            ], 'guid_id');

            if ($result->getErrorCollection()->count()) {
                $error = $result->getErrorCollection()->current();
                $answer->addResponse(new ResponseObject($rtu_item->RTU_Item_ID, new MethodException($error->getMessage(), intval($error->getCode()))));
                $this->client->getLogger()->error($error->getMessage() . " (" . $error->getCode() . ")");
            } else {
                if ($result->getId()) {
                    if ($result instanceof UpdateResult) {
                        $this->client->getLogger()->notice("Обновлена запись Элемента отгрузки {$result->getId()} ({$rtu_item->RTU_Item_ID})");
                    } else {
                        $this->client->getLogger()->notice("Добавлена запись Элемента отгрузки {$result->getId()} ({$rtu_item->RTU_Item_ID})");
                    }
                    $answer->addResponse(new ResponseObject($rtu_item->RTU_Item_ID));
                } else {
                    $answer->addResponse(new ResponseObject($rtu_item->RTU_Item_ID, new MethodException(self::$default_errors[self::ERROR_UNDEFINED], self::ERROR_UNDEFINED)));
                    $this->client->getLogger()->error(self::$default_errors[self::ERROR_UNDEFINED] . " (" . self::ERROR_UNDEFINED . ")");
                }
            }
        }
        return count($answer->Response);
    }
}