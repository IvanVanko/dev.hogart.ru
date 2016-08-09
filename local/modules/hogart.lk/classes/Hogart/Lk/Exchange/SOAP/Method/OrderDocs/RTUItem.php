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
use Hogart\Lk\Exchange\SOAP\MethodException;

/**
 * Обмен с КИС - Позиции реализации товаров и услуг (Отгрузка)
 * @package Hogart\Lk\Exchange\SOAP\Method\OrderDocs
 */
class RTUItem extends AbstractMethod
{
    /**
     * {@inheritDoc}
     */
    function getName()
    {
        return "RTUItem";
    }

    /**
     * @param $rtu_items
     * @return int
     * @throws \Bitrix\Main\ArgumentException
     */
    public function updateRTUItems($rtu_items)
    {
        foreach ($rtu_items as $k => $rtu_item) {
            $rtu = RTUTable::getList([
                'filter'=>[
                    '=guid_id'=>$rtu_item->RTU_ID
                ]
            ])->fetch();
            
            if (empty($rtu['id'])) {
                throw new MethodException(MethodException::ERROR_NO_RTU, [$rtu_item->RTU_ID]);
            }
            
            $order_item = ElementTable::getList([
                'filter'=>[
                    '=XML_ID' => $rtu_item->ID_Item,
                    '=IBLOCK.ID' => new SqlExpression('?i', CATALOG_IBLOCK_ID)
                ]
            ])->fetch();

            if (empty($order_item['ID'])) {
                $n = $k + 1;
                throw new MethodException(MethodException::ERROR_NO_ITEM, [$rtu_item->ID_Item, $n]);
            }
            // данные по Элементам Платежных документов на отгрузку
            $result = RTUItemTable::createOrUpdateByField([
                'd_guid_id' => $rtu_item->RTU_Item_ID,
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
            ], 'd_guid_id');

            if ($result->getErrorCollection()->count()) {
                $error = $result->getErrorCollection()->current();
                throw new MethodException(MethodException::ERROR_BITRIX, [$error->getMessage(), $error->getCode()], $error);
            } else {
                if ($result->getId()) {
                    if ($result instanceof UpdateResult) {
                        $this->client->getLogger()->notice("Обновлена запись Элемента отгрузки {$result->getId()} ({$rtu_item->RTU_Item_ID})");
                    } else {
                        $this->client->getLogger()->notice("Добавлена запись Элемента отгрузки {$result->getId()} ({$rtu_item->RTU_Item_ID})");
                    }
                } else {
                    throw new MethodException(MethodException::ERROR_UNDEFINED);
                }
            }
        }
        return true;
    }
}
