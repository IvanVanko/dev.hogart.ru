<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 03/10/2016
 * Time: 02:02
 */

namespace Hogart\Lk\Entity;


use Bitrix\Main\Entity\DatetimeField;
use Bitrix\Main\Entity\Event;
use Bitrix\Main\Entity\EventResult;
use Bitrix\Main\Entity\Field;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Type\DateTime;
use Hogart\Lk\Field\GuidField;
use Hogart\Lk\Helper\Template\OrderEventNote;
use Ramsey\Uuid\Uuid;

class OrderEventTable extends AbstractEntity
{
    const ORDER_EVENT_ORDER_PAYMENT_SUCCESS = "payment_success";
    const ORDER_EVENT_RTU_SUCCESS = "rtu_success";
    const ORDER_EVENT_ORDER_RTU_CREATE = "order_rtu_create";
    const ORDER_EVENT_PDF_BILL_SUCCESS = "order_pdf_bill_success";
    const ORDER_EVENT_PDF_KP_SUCCESS = "order_pdf_kp_success";

    const ENTITY_ORDER_RTU = __NAMESPACE__ . "\\OrderRTUTable";
    const ENTITY_RTU = __NAMESPACE__ . "\\RTUTable";
    const ENTITY_ORDER_PAYMENT = __NAMESPACE__ . "\\OrderPaymentTable";
    const ENTITY_PDF = __NAMESPACE__ . "\\PdfTable";

    private static $events = [
        self::ORDER_EVENT_ORDER_PAYMENT_SUCCESS => self::ENTITY_ORDER_PAYMENT,
        self::ORDER_EVENT_RTU_SUCCESS => self::ENTITY_RTU,
        self::ORDER_EVENT_ORDER_RTU_CREATE => self::ENTITY_ORDER_RTU,
        self::ORDER_EVENT_PDF_BILL_SUCCESS => self::ENTITY_PDF,
        self::ORDER_EVENT_PDF_KP_SUCCESS => self::ENTITY_PDF,
    ];

    /**
     * Returns DB table name for entity
     *
     * @return string
     */
    public static function getTableName()
    {
        return "h_order_event";
    }

    /**
     * Returns entity map definition
     */
    public static function getMap()
    {
        return [
            new GuidField("guid_id", [
                'primary' => true
            ]),
            new DatetimeField("created_at", [
                'default_value' => new DateTime()
            ]),
            new IntegerField("order_id"),
            new ReferenceField("order", __NAMESPACE__ . "\\OrderTable", ["=this.order_id" => "ref.id"]),
            new StringField("event", [
                "required" => true,
                'validation' => [__CLASS__, 'validateEvent'],
            ]),
            new StringField("entity", [
                "required" => true,
            ]),
            new StringField("entity_id", [
                "required" => true,
            ])
        ];
    }

    public static function validateEvent()
    {
        return [
            function ($value, $primary, array $row, Field $field) {
                if(in_array($value, array_keys(self::$events))) {
                    return true;
                }
                return "Неизвестное событие {$value}";
            }
        ];
    }

    public static function getOrderHistory($order_id)
    {
        $history = array_reduce(self::getList([
            'filter' => [
                '=order_id' => $order_id
            ],
            'order' => [
                'created_at' => 'DESC'
            ]
        ])->fetchAll(), function ($result, $event) {
            if (($event_note = self::getEntityNote($event)) && $event_note instanceof OrderEventNote) {
                $event_note->setGuid($event['guid_id']);
                $result[] = $event_note;
            }
            return $result;
        }, []);

        usort($history, function (OrderEventNote $a, OrderEventNote $b) {
            return $a->getDate()->getTimestamp() < $b->getDate()->getTimestamp();
        });

        return $history;
    }

    public static function onBeforeAdd(Event $event)
    {
        $fields = $event->getParameter('fields');
        $result = new EventResult();
        $result->modifyFields([
            'entity' => self::$events[$fields['event']],
            'guid_id' => Uuid::uuid4()->toString()
        ]);
        return $result;
    }

    public static function getEntityNote($event)
    {
        $class = self::$events[$event['event']];
        if (method_exists($class, "getOrderEventNote")) {
            return call_user_func_array([$class, "getOrderEventNote"], [$event['entity_id'], $event]);
        }
    }
}