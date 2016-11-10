<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 01/08/16
 * Time: 15:56
 */

namespace Hogart\Lk\Entity;


use Bitrix\Main\DB\SqlExpression;
use Bitrix\Main\Entity\DateField;
use Bitrix\Main\Entity\EnumField;
use Bitrix\Main\Entity\Event;
use Bitrix\Main\Entity\EventResult;
use Bitrix\Main\Entity\FloatField;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Type\Date;
use Doctrine\Common\Annotations\Annotation\Enum;
use Hogart\Lk\Field\GuidField;

/**
 * Таблица Позиции Заказа
 * @package Hogart\Lk\Entity
 */
class OrderItemTable extends AbstractEntity
{
    /** Статус - Не определен */
    const STATUS_NOT_PROVIDED = 0;
    /** Статус -  */
    const STATUS_SUPPLIER_ORDER = 1;
    /** Статус -  */
    const STATUS_INTERMEDIATE_STORE = 2;
    /** Статус -  */
    const STATUS_IN_RESERVE = 3;
    /** Статус -  */
    const STATUS_SHIPMENT_PROCESS = 4;
    /** Статус -  */
    const STATUS_SHIPMENT = 5;

    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return "h_order_item";
    }

    /**
     * {@inheritDoc}
     */
    public static function getMap()
    {
        return [
            new IntegerField("id", [
                'primary' => true,
                "autocomplete" => true
            ]),
            new IntegerField("order_id"),
            new ReferenceField("order", __NAMESPACE__ . "\\OrderTable", ["=this.order_id" => "ref.id"]),
            new IntegerField("string_number"),
            new IntegerField("item_id"),
            new ReferenceField("item", "Bitrix\\Iblock\\ElementTable", ["=this.item_id" => "ref.ID", "=ref.IBLOCK_ID" => new SqlExpression('?i', CATALOG_IBLOCK_ID)]),
            new IntegerField("count"),
            new FloatField("price"),
            new FloatField("discount"),
            new FloatField("discount_price"),
            new FloatField("total"),
            new FloatField("total_vat"),
            new EnumField("status", [
                'values' => [
                    self::STATUS_NOT_PROVIDED,
                    self::STATUS_SUPPLIER_ORDER,
                    self::STATUS_INTERMEDIATE_STORE,
                    self::STATUS_IN_RESERVE,
                    self::STATUS_SHIPMENT_PROCESS,
                    self::STATUS_SHIPMENT,
                ],
                'default_value' => self::STATUS_NOT_PROVIDED
            ]),
            new DateField("delivery_time", [
                'default_value' => '0000-00-00'
            ]), // Ориентировочный срок поставки если 1 или 2(заказан у поставщика)
            new StringField("item_group")
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected static function getIndexes()
    {
        return [
//            new Index('idx_d_guid_id', ['d_guid_id' => 73]),
            new Index('idx_order_item_entity_most', ['order_id', 'item_id', 'status']),
        ];
    }

    public static function showStatusText($status)
    {
        return [
            self::STATUS_NOT_PROVIDED => 'Не обеспечен',
            self::STATUS_SUPPLIER_ORDER => 'Заказан',
            self::STATUS_INTERMEDIATE_STORE => 'На пром. складе',
            self::STATUS_IN_RESERVE => 'В резерве',
            self::STATUS_SHIPMENT_PROCESS => 'В процессе отгрузки',
            self::STATUS_SHIPMENT => 'Отгружен',
        ][$status];
    }

    public static function getStatusColor($status)
    {
        return [
            self::STATUS_NOT_PROVIDED => 'default',
            self::STATUS_SUPPLIER_ORDER => 'danger',
            self::STATUS_INTERMEDIATE_STORE => 'danger',
            self::STATUS_IN_RESERVE => 'warning',
            self::STATUS_SHIPMENT_PROCESS => 'warning',
            self::STATUS_SHIPMENT => 'primary',
        ][$status];
    }

    /**
     * Удаление записей таблицы по номеру заказа
     * @param int $id
     * @return bool
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Exception
     */
    public static function deleteByOrderId($id)
    {
        $items = self::getList([
            'filter' => [
                '=order_id' => intval($id)
            ]
        ]);
        while (($orderItem = $items->fetch())) {
            self::delete($orderItem['id']);
        }
        return true;
    }

    public static function onBeforeAdd(Event $event)
    {
        $fields = $event->getParameter('fields');
        $result = new EventResult();
        $result->modifyFields([
            'status' => intval($fields['status'])
        ]);
        return $result;
    }


}