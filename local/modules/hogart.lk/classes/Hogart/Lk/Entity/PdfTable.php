<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 10/10/2016
 * Time: 19:27
 */

namespace Hogart\Lk\Entity;


use Bitrix\Main\Entity\Event;
use Bitrix\Main\Entity\EventResult;
use Bitrix\Main\Entity\Field;
use Bitrix\Main\Entity\StringField;
use Hogart\Lk\Exchange\RabbitMQ\Exchange\PdfExchange;
use Hogart\Lk\Exchange\SOAP\Request\Pdf;
use Hogart\Lk\Field\GuidField;
use Hogart\Lk\Helper\Template\Message;
use Hogart\Lk\Helper\Template\OrderEventNote;
use Ramsey\Uuid\Uuid;

class PdfTable extends AbstractEntity implements IOrderEventNote
{
    const TYPE_BILL = "bill";
    const TYPE_KP = "kp";

    const ENTITY_ORDER = __NAMESPACE__ . "\\OrderTable";

    public static $types = [
        self::TYPE_BILL => self::ENTITY_ORDER,
        self::TYPE_KP => self::ENTITY_ORDER,
    ];

    /**
     * @inheritDoc
     */
    public static function getTableName()
    {
        return "h_pdf";
    }

    /**
     * @inheritDoc
     */
    public static function getMap()
    {
        return [
            new GuidField("guid_id", [
                'primary' => true
            ]),
            new StringField("type", [
                "required" => true,
                'validation' => [__CLASS__, 'validateType'],
            ]),
            new StringField("entity", [
                "required" => true,
            ]),
            new StringField("entity_id", [
                "required" => true,
            ]),
            new StringField("path")
        ];
    }

    public static function validateType()
    {
        return [
            function ($value, $primary, array $row, Field $field) {
                if(in_array($value, array_keys(self::$types))) {
                    return true;
                }
                return "Неизвестный тип {$value}";
            }
        ];
    }

    public static function getByEntityClass($entity, $entity_id)
    {
        return array_reduce(self::getList([
            'filter' => [
                '=entity_id' => $entity_id,
                '=entity' => $entity
            ]
        ])->fetchAll(), function ($result, $entity) {
            $result[$entity['type']] = $entity;
            return $result;
        }, []);
    }

    /**
     * @inheritDoc
     */
    public static function add(array $data)
    {
        $guid = self::getUUID($data);
        file_put_contents(HOGART_PDF_DIR . "/{$guid}.pdf", $data['data'], FILE_BINARY);
        unset($data['data']);
        return parent::add($data);
    }

    public static function pdfRequest($entity_id, $type)
    {
        $request = new Pdf($entity_id, $type);
        self::publishToRabbit(new PdfExchange(), $request, 'request');
    }

    public static function getUUID($fields)
    {
        return Uuid::uuid5(Uuid::NAMESPACE_OID, implode('|', [
            $fields['type'],
            self::$types[$fields['type']],
            $fields['entity_id']
        ]))->toString();
    }

    public static function onBeforeAdd(Event $event)
    {
        $fields = $event->getParameter('fields');
        $guid = self::getUUID($fields);
        $result = new EventResult();
        $result->modifyFields([
            'entity' => self::$types[$fields['type']],
            'guid_id' => $guid,
            'path' => HOGART_PDF_DIR . "/{$guid}.pdf"
        ]);
        return $result;
    }

    public static function getTitle($entity_id)
    {
        $pdf = self::getByField("guid_id", $entity_id);
        switch ($pdf['type']) {
            case self::TYPE_BILL:
                return "Счет на оплату заказа №%s";
            case self::TYPE_KP:
                return "Коммерческое предложение по заказу №%s";
        }
    }

    static function getOrderEventNote($entity_id, $event)
    {
        switch ($event['event']) {
            case OrderEventTable::ORDER_EVENT_PDF_BILL_SUCCESS:
            case OrderEventTable::ORDER_EVENT_PDF_KP_SUCCESS:
                $order = OrderTable::getRowById($event['order_id']);
                $title = vsprintf(self::getTitle($entity_id) . " <abbr title='скачать'>(скачать)</abbr>", [($order['number'] ? : "<sup>получение</sup>")]);
                $note = new OrderEventNote($title, $event['created_at']);
                $note
                    ->setLink("/account/orders/pdf/" . $event['order_id'] . "/" . $entity_id)
                    ->setBadgeIcon('<i class="fa fa-file-text-o" aria-hidden="true"></i>')
                    ->setBadgeClass('warning')
                ;
                return $note;
                break;
        }
    }

    public static function onAfterAdd(Event $event)
    {
        $id = $event->getParameter('primary')['guid_id'];
        $fields = $event->getParameter('fields');
        if ($fields['entity'] == self::ENTITY_ORDER) {
            $relation_events = [
                self::TYPE_BILL => OrderEventTable::ORDER_EVENT_PDF_BILL_SUCCESS,
                self::TYPE_KP => OrderEventTable::ORDER_EVENT_PDF_KP_SUCCESS,
            ];

            OrderEventTable::add([
                'entity_id' => $id,
                'event' => $relation_events[$fields['type']],
                'order_id' => $fields['entity_id'],
            ]);

            $order = OrderTable::getRowById($fields['entity_id']);
            $message = new Message(
                vsprintf(self::getTitle($id), [($order['number'] ? : "<sup>получение</sup>")]) . " <b>(скачать)</b>",
                Message::SEVERITY_INFO
            );
            $message
                ->setIcon('fa fa-file-text-o')
                ->setUrl("/account/orders/pdf/" . $fields['entity_id'] . "/" . $id)
            ;
            FlashMessagesTable::addNewMessage($order['account_id'], $message);
        }
    }
}
