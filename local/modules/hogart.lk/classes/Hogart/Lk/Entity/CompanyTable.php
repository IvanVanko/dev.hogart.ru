<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 27/07/16
 * Time: 22:44
 */

namespace Hogart\Lk\Entity;

use Bitrix\Main\Entity\BooleanField;
use Bitrix\Main\Entity\DateField;
use Bitrix\Main\Entity\EnumField;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Entity\StringField;
use Hogart\Lk\Field\GuidField;

class CompanyTable extends AbstractEntity
{
    const TYPE_LEGAL_ENTITY = 1;
    const TYPE_INDIVIDUAL_ENTREPRENEUR = 2;
    const TYPE_INDIVIDUAL = 3;

    const DOC_PASSPORT = 1;
    const DOC_NO_PASSPORT = 2;


    /**
     * @inheritDoc
     */
    public static function getTableName()
    {
        return 'h_company';
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
            new IntegerField("user_id"),
            new ReferenceField("user", "Bitrix\\Main\\UserTable", ["=this.user_id" => "ref.ID"]),
            new GuidField("guid_id"),
            new StringField("name"),
            new EnumField("type", [
                'values' => [
                    self::TYPE_LEGAL_ENTITY,
                    self::TYPE_INDIVIDUAL_ENTREPRENEUR,
                    self::TYPE_INDIVIDUAL
                ]
            ]),
            new StringField("type_form"),
            new IntegerField("kind_activity_id"),
            new ReferenceField("kind_activity", "KindOfActivityTable", ["=this.kind_activity_id" => "ref.id"]),
            new StringField("inn"),
            new StringField("kpp"),
            new DateField("date_fact_address"),
            new IntegerField("chief_id"),
            new ReferenceField("chief", "ContactInfoTable", ["=this.chief_id" => "ref.id"]),
            new StringField("certificate_number"),
            new DateField("certificate_date"),
            new EnumField("doc_pass", [
                'values' => [
                    self::DOC_PASSPORT,
                    self::DOC_NO_PASSPORT,
                ]
            ]),
            new StringField("doc_serial"),
            new StringField("doc_number"),
            new StringField("doc_ufms"),
            new DateField("doc_date"),
            new BooleanField("is_active")
        ];
    }

}