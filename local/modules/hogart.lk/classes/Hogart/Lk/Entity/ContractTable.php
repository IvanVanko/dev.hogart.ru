<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 29/07/16
 * Time: 16:11
 */

namespace Hogart\Lk\Entity;


use Bitrix\Main\Entity\BooleanField;
use Bitrix\Main\Entity\DateField;
use Bitrix\Main\Entity\EnumField;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Entity\StringField;
use Hogart\Lk\Field\GuidField;

class ContractTable extends AbstractEntity
{
    const VAT_0 = 0;
    const VAT_10 = 10;
    const VAT_18 = 18;

    /**
     * @inheritDoc
     */
    public static function getTableName()
    {
        return "h_contract";
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
            new IntegerField("company_id"),
            new ReferenceField("company", "CompanyTable", ["=this.company_id" => "ref.id"]),
            new IntegerField("hogart_company_id"),
            new ReferenceField("hogart_company", "HogartCompanyTable", ["=this.hogart_company_id" => "ref.id"]),
            new GuidField("hogart_id"),
            new StringField("number"),
            new DateField("start_date"),
            new DateField("end_date"),
            new BooleanField("extension"),
            new IntegerField("currency_id"),
            new BooleanField("perm_item"),
            new BooleanField("prem_promo"),
            new BooleanField("perm_clearing"),
            new BooleanField("perm_card"),
            new BooleanField("perm_cash"),
            new BooleanField("cash_control"),
            new StringField("cash_limit"),
            new StringField("deferral"),
            new StringField("credit_limit"),
            new BooleanField("have_original"),
            new BooleanField("accept"),
            new EnumField("vat_rate", [
                'values' => [
                    self::VAT_0,
                    self::VAT_10,
                    self::VAT_18
                ],
                'default_value' => self::VAT_18
            ]),
            new BooleanField("vat_include"),
            new BooleanField("is_active")
        ];
    }

}