<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 29/07/16
 * Time: 16:39
 */

namespace Hogart\Lk\Entity;


use Bitrix\Main\Entity\BooleanField;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Entity\StringField;
use Hogart\Lk\Field\GuidField;

class PaymentAccountTable extends AbstractEntity
{
    /**
     * @inheritDoc
     */
    public static function getTableName()
    {
        return "h_payment_account";
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
            new StringField("number"),
            new StringField("currency_code"),
            new ReferenceField("currency", "Bitrix\\Currency\\CurrencyTable", ["=this.currency_code" => "ref.CURRENCY"]),
            new StringField("bik"),
            new StringField("bank_name"),
            new StringField("corr_number"),
            new BooleanField("is_active")
        ];
    }

    /**
     * @inheritDoc
     */
    protected static function getIndexes()
    {
        return [
            new Index("idx_guid_id", ["guid_id" => 36])
        ];
    }

}