<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 01/08/16
 * Time: 16:10
 */

namespace Hogart\Lk\Entity;

use Bitrix\Main\Entity\DatetimeField;
use Bitrix\Main\Entity\Event;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Entity\EnumField;
use Bitrix\Main\Entity\FloatField;
use Bitrix\Main\Entity\BooleanField;
use Dompdf\Dompdf;
use Dompdf\Options;
use Hogart\Lk\Field\GuidField;
use Bitrix\Main\Entity\DateField;
use Hogart\Lk\Helper\Template\FlashInfo;
use Hogart\Lk\Helper\Template\FlashSuccess;
use Hogart\Lk\Helper\Template\Money;
use Hogart\Lk\Helper\Template\OrderEventNote;

/**
 * Таблица Платежи Заказа
 * @package Hogart\Lk\Entity
 */
class OrderPaymentTable extends AbstractEntity implements IOrderEventNote
{
    /** Вид оплаты - Наличные */
    const PAYMENT_FORM_CASH = 1;
    /** Вид оплаты - Банковский платеж */
    const PAYMENT_FORM_BANK = 2;
    /** Вид оплаты - По карте */
    const PAYMENT_FORM_CARD = 3;

    /** Направление - Входящий */
    const DIRECTION_INCOME = 1;
    /** Направление - Исходящий */
    const DIRECTION_OUTCOME = 2;

    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return "h_order_payment";
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
            new GuidField("guid_id"),
            new IntegerField("order_id"),
            new ReferenceField("order", __NAMESPACE__ . "\\OrderTable", ["=this.order_id" => "ref.id"]),

            new DatetimeField("payment_date"),
            new StringField("number"),
            new EnumField("form", [
                'values' => [
                    self::PAYMENT_FORM_CASH,
                    self::PAYMENT_FORM_BANK,
                    self::PAYMENT_FORM_CARD,
                ]
            ]),
            new EnumField("direction", [
                'values' => [
                    self::DIRECTION_INCOME,
                    self::DIRECTION_OUTCOME,
                ]
            ]),
            new FloatField("total"),
            new StringField("currency_code"),
            new ReferenceField("currency", "Bitrix\\Currency\\CurrencyTable", ["=this.currency_code" => "ref.CURRENCY"]),
            new BooleanField("is_active")
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected static function getIndexes()
    {
        return [
            new Index('idx_guid_id', ['guid_id' => 36]),
            new Index('idx_order_entity_most', ['order_id']),
            new Index('idx_is_active', ['is_active']),
        ];
    }

    public static function showFormText($form)
    {
        return [
            self::PAYMENT_FORM_CASH => "наличные",
            self::PAYMENT_FORM_BANK => "банковский платеж",
            self::PAYMENT_FORM_CARD => "пластиковая карта",
        ][$form];
    }

    public static function getOrderEventNote($entity_id, $event)
    {
        $note = new OrderEventNote();
        $payment = self::getRowById($entity_id);
        $note->setTitle(vsprintf(
            "Платеж №%s от %s на сумму <span class='money-" . strtolower($payment['currency_code']) . "'>%s</span>",
            [
                $payment['number'],
                $payment['payment_date']->format(HOGART_DATE_FORMAT),
                Money::show($payment['total'])
            ]
        ));

        $note
            ->setTemplateFile($event["event"] . ".php")
            ->setTemplateData(['payment' => $payment, 'event' => $event])
            ->setBadgeIcon('<i class="fa fa-' . strtolower($payment['currency_code']) . '" aria-hidden="true"></i>')
            ->setBadgeClass('warning')
            ->setDate($payment['payment_date'])
        ;

        return $note;
    }

    public static function createPaymentByUser($order_id, $form, $sum, \CBitrixComponent $component = null)
    {
        $order = OrderTable::getOrder($order_id);
        switch ($form) {
            case self::PAYMENT_FORM_BANK:
                PdfTable::pdfRequest($order['guid_id'], PdfTable::TYPE_BILL);
                new FlashSuccess("Вы будете уведомлены по готовности счета!");
                break;
        }
    }

    public static function onAfterAdd(Event $event)
    {
        $id = $event->getParameter('id');
        $fields = $event->getParameter('fields');
        OrderEventTable::add([
            'entity_id' => $id,
            'event' => OrderEventTable::ORDER_EVENT_ORDER_PAYMENT_SUCCESS,
            'order_id' => $fields['order_id'],
        ]);
    }
}