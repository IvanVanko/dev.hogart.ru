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

class OrderPaymentTable extends AbstractEntity
{
    const PAYMENT_FORM_CASH = 0;
    const PAYMENT_FORM_BANK = 1;
    const PAYMENT_FORM_CARD = 2;

    const DIRECTION_INCOME = 0;
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
            new IntegerField("order_id"),
            new ReferenceField("order", "OrderTable", ["=this.order_id" => "ref.id"]),
            new GuidField("guid_id"),

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

}