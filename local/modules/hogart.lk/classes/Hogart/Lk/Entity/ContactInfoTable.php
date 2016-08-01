<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 28/07/16
 * Time: 15:29
 */

namespace Hogart\Lk\Entity;


use Bitrix\Main\Entity\BooleanField;
use Bitrix\Main\Entity\EnumField;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Entity\StringField;
use Hogart\Lk\Field\GuidField;

class ContactInfoTable extends AbstractEntity
{
    const TYPE_PHONE = 1;
    const TYPE_EMAIL = 2;

    const PHONE_KIND_MOBILE = 1;
    const PHONE_KIND_STATIC = 2;


    /**
     * @inheritDoc
     */
    public static function getTableName()
    {
        return 'h_contact_info';
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
            new IntegerField("contact_id"),
            new ReferenceField("contact", "ContactTable", ["=this.contact_id" => "ref.id"]),
            new EnumField("type", [
                'values' => [
                    self::TYPE_PHONE,
                    self::TYPE_EMAIL
                ]
            ]),
            new EnumField("phone_kind", [
                'values' => [
                    self::TYPE_PHONE,
                    self::TYPE_EMAIL
                ]
            ]),
            new StringField("value"),
            new BooleanField("is_active")
        ];
    }

    /**
     * @inheritDoc
     */
    protected static function getIndexes()
    {
        return [
            new Index("idx_guid_id", ["guid_id" => 36]),
            new Index("idx_contact_id", ["contact_id"]),
        ];
    }
}