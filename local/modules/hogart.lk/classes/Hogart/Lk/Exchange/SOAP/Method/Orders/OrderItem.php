<?php
/**
 * By: Ivan Kiselev aka shaqito[at]gmail.com
 * Via: PhpStorm.
 * At: 04.08.2016 10:01
 */

namespace Hogart\Lk\Exchange\SOAP\Method\Orders;

use Bitrix\Iblock\ElementTable;
use Bitrix\Main\DB\SqlExpression;
use Hogart\Lk\Entity\OrderItemTable;
use Hogart\Lk\Entity\OrderTable;
use Hogart\Lk\Exchange\SOAP\AbstractMethod;
use Bitrix\Main\Entity\UpdateResult;
use Hogart\Lk\Exchange\SOAP\Method\MethodException;
use Hogart\Lk\Exchange\SOAP\Method\Response;
use Hogart\Lk\Exchange\SOAP\Method\ResponseObject;

/**
 * Class Company - добавление Компании и Видов деятельности
 * @package Hogart\Lk\Exchange\SOAP\Method
 */
class OrderItem extends AbstractMethod
{
    /**
     * @inheritDoc
     */
    function getName()
    {
        return "OrderItem";
    }


    /**
     * @param $orderItems
     * @return int
     */
    public function updateOrderItems($orderItems)
    {
        $order = null;
        foreach ($orderItems as $k => $orderItem) {
            if (null === $order) {
                $order = OrderTable::getByField('guid_id', $orderItem->Order_ID);
                if (!isset($order)) {
                    throw new MethodException("Не найден Заказ({$orderItem->ID_Item})");
                }
            }
            $item = ElementTable::getList([
                'filter'=>[
                    '=XML_ID' => $orderItem->ID_Item,
                    '=IBLOCK.ID' => new SqlExpression('?i', CATALOG_IBLOCK_ID)
                ]
            ])->fetch();
            if (!isset($item)) {
                $n = $k + 1;
                throw new MethodException("Не найдена позиция Заказа({$orderItem->ID_Item}): порядковый номер - {$n}, ID - {$orderItem->ID_Item}");
            }
            $data = [
                'd_guid_id' => $orderItem->Order_Item_ID,
                'order_id' => $order['id'],
                'string_number' => $orderItem->Order_Line_Number,
                'item_id' => $item['id'],
                'acu' => $orderItem->Item_Article ?: '',
                'name' => $orderItem->Item_Name,
                'count' => $orderItem->Count,
                'cost' => $orderItem->Cost,
                'discount' => $orderItem->Discount,
                'discount_cost' => $orderItem->Cost_Disc,
                'total' => $orderItem->Summ,
                'total_vat' => $orderItem->Sum_VAL,
                'status' => (int)$orderItem->Status_Item,
                'delivery_time' => $orderItem->Delivery_Time,
                'group' => $orderItem->Group,
            ];
            $result = OrderItemTable::createOrUpdateByField($data, 'd_guid_id');

            if ($result->getErrorCollection()->count()) {
                $error = $result->getErrorCollection()->current();
                throw new MethodException($error->getMessage(), intval($error->getCode()));
            } else {
                if ($result->getId()) {
                    if ($result instanceof UpdateResult) {
                        $this->client->getLogger()->notice("Обновлена запись Состава заказа {$result->getId()} ({$orderItem->Order_Item_ID})");
                    } else {
                        $this->client->getLogger()->notice("Добавлена запись Состава заказа {$result->getId()} ({$orderItem->Order_Item_ID})");
                    }
                } else {
                    throw new MethodException(self::$default_errors[self::ERROR_UNDEFINED], self::ERROR_UNDEFINED);
                }
            }
        }
        return true;
    }
}