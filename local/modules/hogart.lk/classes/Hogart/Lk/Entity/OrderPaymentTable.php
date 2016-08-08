<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 01/08/16
 * Time: 16:10
 */

namespace Hogart\Lk\Entity;

use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Entity\EnumField;
use Bitrix\Main\Entity\FloatField;
use Bitrix\Main\Entity\BooleanField;
use Hogart\Lk\Field\GuidField;
use Bitrix\Main\Entity\DateField;

/**
 * Таблица Платежи Заказа
 * @package Hogart\Lk\Entity
 */
class OrderPaymentTable extends AbstractEntity
{
    /** Вид оплаты - Наличные */
    const PAYMENT_FORM_CASH = 0;
    /** Вид оплаты - Банковский платеж */
    const PAYMENT_FORM_BANK = 1;
    /** Вид оплаты - По карте */
    const PAYMENT_FORM_CARD = 2;

    /** Направление - Входящий */
    const DIRECTION_INCOME = 0;
    /** Направление - Исходящий */
    const DIRECTION_OUTCOME = 1;

    /**
     * @inheritDoc
     */
    public static function getTableName()
    {
        return "h_order_payment";
    }

    /**
     * @inheritDoc
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
            new ReferenceField("order", "OrderTable", ["=this.order_id" => "ref.id"]),

            new DateField("payment_date"),
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
     * @inheritDoc
     */
    protected static function getIndexes()
    {
        return [
            new Index('idx_guid_id', ['guid_id' => 36]),
            new Index('idx_order_entity_most', ['order_id']),
            new Index('idx_is_active', ['is_active']),
        ];
    }
}