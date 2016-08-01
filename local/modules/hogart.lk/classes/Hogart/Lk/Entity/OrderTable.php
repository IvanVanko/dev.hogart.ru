<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 01/08/16
 * Time: 15:11
 */

namespace Hogart\Lk\Entity;


use Bitrix\Main\Entity\BooleanField;
use Bitrix\Main\Entity\DateField;
use Bitrix\Main\Entity\EnumField;
use Bitrix\Main\Entity\FloatField;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Entity\TextField;
use Hogart\Lk\Field\GuidField;

class OrderTable extends AbstractEntity
{
    const TYPE_SALE = 1;
    const TYPE_PROMO = 2;

    const STATUS_NEW = 1;
    const STATUS_IN_WORK = 2;
    const STATUS_FINISHED = 3;


    /**
     * @inheritDoc
     */
    public static function getTableName()
    {
        return "h_order";
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
            new IntegerField("company_id"),
            new ReferenceField("company", "ClientCompanyTable", ["=this.company_id" => "ref.id"]),
            new IntegerField("hogart_company_id"),
            new ReferenceField("hogart_company", "HogartCompanyTable", ["=this.hogart_company_id" => "ref.id"]),
            new StringField("number"),
            new DateField("order_date"),
            new EnumField("order_type", [
                'values' => [
                    self::TYPE_SALE,
                    self::TYPE_PROMO
                ]
            ]),
            new IntegerField("contract_id"),
            new ReferenceField("contract", "ContractTable", ["=this.contract_id" => "ref.id"]),
            new EnumField("order_status", [
                'values' => [
                    self::STATUS_NEW,
                    self::STATUS_IN_WORK,
                    self::STATUS_FINISHED,
                ]
            ]),
            new IntegerField("store_id"),
            new ReferenceField("store", "Bitrix\\Catalog\\StoreTable", ["=this.store_id" => "ref.ID"]),
            new IntegerField("user_id"),
            new ReferenceField("user", "Bitrix\\Main\\UserTable", ["=this.user_id" => "ref.ID"]),
            new IntegerField("staff_id"),
            new ReferenceField("staff", "StaffTable", ["=this.staff_id" => "ref.id"]),
            new TextField("note"),
            new BooleanField("sale_granted"),
            new FloatField("sale_max_money"),
            new BooleanField("perm_bill"),
            new BooleanField("perm_reserve"),
            new StringField("currency_code"),
            new ReferenceField("currency", "Bitrix\\Currency\\CurrencyTable", ["=this.currency_code" => "ref.CURRENCY"]),
            new BooleanField("is_active")
        ];
    }

}