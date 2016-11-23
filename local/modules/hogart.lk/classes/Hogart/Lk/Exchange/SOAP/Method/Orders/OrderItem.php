<?php
/**
 * By: Ivan Kiselev aka shaqito[at]gmail.com
 * Via: PhpStorm.
 * At: 04.08.2016 10:01
 */

namespace Hogart\Lk\Exchange\SOAP\Method\Orders;

use Bitrix\Iblock\ElementTable;
use Bitrix\Main\DB\SqlExpression;
use Bitrix\Main\Type\Date;
use Hogart\Lk\Entity\OrderItemTable;
use Hogart\Lk\Entity\OrderTable;
use Hogart\Lk\Exchange\SOAP\AbstractMethod;
use Bitrix\Main\Entity\UpdateResult;
use Hogart\Lk\Exchange\SOAP\MethodException;

/**
 * Class Company - добавление Компании и Видов деятельности
 * @package Hogart\Lk\Exchange\SOAP\Method
 */
class OrderItem extends AbstractMethod
{
    /**
     * {@inheritDoc}
     */
    function getName()
    {
        return "OrderItem";
    }


    /**
     * @param array $orderItems
     * @param string $orderGuid
     * @return int
     */
    public function updateOrderItems($orderItems, $orderGuid)
    {
        $order = OrderTable::getByField('guid_id', $orderGuid);

        if (null === $order) {
            if (!isset($order)) {
                throw new MethodException(MethodException::ERROR_NO_ORDER, [$orderGuid]);
            }
        }
        // Выбираем текущие записи заказа, чтобы их по порядку заменить,
        // т.к. нет уникального общего ID в двух системах
        $existing_items = OrderItemTable::getList([
            'filter' => [
                '=order_id' => $order['id']
            ],
            'order' => [
                'string_number' => 'ASC'
            ]
        ])->fetchAll();

        foreach ($orderItems as $k => $orderItem) {

            $item = ElementTable::getList([
                'filter'=>[
                    '=XML_ID' => $orderItem->ID_Item,
                    '=IBLOCK.ID' => new SqlExpression('?i', CATALOG_IBLOCK_ID)
                ]
            ])->fetch();
            $item_id = $item['ID'];
            if (empty($item_id)) {
                // создаем позицию скрытую от глаз, если уже не получится - исключение
                $propA = [];
                $propA['sku'] = $orderItem->Item_Article;
                $propA['default_count'] = floatval($orderItem->Item_default_count);
                $arLoadProductArray = Array(
                    "IBLOCK_ID" => CATALOG_IBLOCK_ID,
                    "XML_ID" => $orderItem->ID_Item,
                    "PROPERTY_VALUES" => $propA,
                    "NAME" => $orderItem->Item_Name,
                    "ACTIVE" => "N"
                );
                $item_id = (new \CIBlockElement())->Add($arLoadProductArray, false, false, false);
                if (empty($item_id)) {
                    throw new MethodException(MethodException::ERROR_NO_ITEM, [$orderItem->Order_ID, $orderItem->Order_Line_Number]);
                }

                $measure = \CCatalogMeasure::getList([], ["CODE" => $orderItem->Item_unit_messure_catalog_id])->Fetch();
                $measure_id = $measure['ID'];
                if (empty($measure)) {
                    $measure_id = \CCatalogMeasure::add(array(
                        'MEASURE_TITLE' => $orderItem->Item_unit_messure_catalog_name,
                        'SYMBOL_RUS' => $orderItem->Item_unit_messure_catalog_name,
                        'SYMBOL_INTL' => $orderItem->Item_unit_messure_catalog_name,
                        'SYMBOL_LETTER_INTL' => $orderItem->Item_unit_messure_catalog_name,
                        'CODE' => $orderItem->Item_unit_messure_catalog_id
                    ));
                }
                \CCatalogProduct::Add([
                    "ID" => $item_id,
                    "VAT_ID" => 1,
                    "VAT_INCLUDED" => "Y",
                    "MEASURE" => $measure_id
                ]);
            }
            $data = [
                'order_id' => $order['id'],
                'string_number' => $orderItem->Order_Line_Number,
                'item_id' => $item_id,
                'count' => $orderItem->Count,
                'price' => $orderItem->Cost,
                'discount' => floatval($orderItem->Discount),
                'discount_price' => floatval($orderItem->Cost_Disc),
                'total' => floatval($orderItem->Summ),
                'total_vat' => floatval($orderItem->Sum_VAL),
                'status' => (int)$orderItem->Status_Item,
                'delivery_time' => $orderItem->Delivery_Time ? new Date($orderItem->Delivery_Time, 'Y-m-d') : null,
                'item_group' => (string)$orderItem->Group,
            ];
            if (($existing_item = array_shift($existing_items))) {
                $data['id'] = $existing_item['id'];
            }
            $result = OrderItemTable::createOrUpdateByField($data, 'id');

            if ($result->getErrorCollection()->count()) {
                $error = $result->getErrorCollection()->current();
                throw new MethodException(MethodException::ERROR_BITRIX, [$error->getMessage(), $error->getCode()]);
            } else {
                if ($result->getId()) {
                    if ($result instanceof UpdateResult) {
                        $this->client->getLogger()->notice("Обновлена запись Состава заказа {$result->getId()} ({$orderItem->Order_Item_ID})");
                    } else {
                        $this->client->getLogger()->notice("Добавлена запись Состава заказа {$result->getId()} ({$orderItem->Order_Item_ID})");
                    }
                } else {
                    throw new MethodException(MethodException::ERROR_UNDEFINED);
                }
            }
        }
        // Удаляем лишние записи в обновленном заказе
        if (!empty($existing_items)) {
            foreach ($existing_items as $existing_item) {
                OrderItemTable::delete($existing_item['id']);
            }
        }
        return true;
    }
}