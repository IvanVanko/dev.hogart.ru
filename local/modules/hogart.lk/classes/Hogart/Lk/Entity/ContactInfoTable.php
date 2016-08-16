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
use Bitrix\Main\Entity\StringField;
use Hogart\Lk\Field\GuidField;

/**
 * Таблица Контактной информации
 * @package Hogart\Lk\Entity
 */
class ContactInfoTable extends AbstractEntityRelation
{
    /** Тип - Телефон */
    const TYPE_PHONE = 1;
    /** Тип - Email */
    const TYPE_EMAIL = 2;

    /** Тип телефона - Мобильный */
    const PHONE_KIND_MOBILE = 1;
    /** Тип телефона - Стационарный */
    const PHONE_KIND_STATIC = 2;


    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return 'h_contact_info';
    }

    /**
     * {@inheritDoc}
     */
    public static function getMap()
    {
        return array_merge([
            new GuidField("d_guid_id", [
                'format' => '%(^[0-9a-z]{8}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{12}[_][0-9a-z]{8}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{12}$|^$)%',
                'unique' => false
            ]),
            new EnumField("info_type", [
                'values' => [
                    self::TYPE_PHONE,
                    self::TYPE_EMAIL
                ],
                'primary' => true
            ]),
            new EnumField("phone_kind", [
                'values' => [
                    self::PHONE_KIND_MOBILE,
                    self::PHONE_KIND_STATIC
                ],
                'primary' => true,
                'default_value' => 0
            ]),
            new StringField("value"),
            new BooleanField("is_active")
        ], parent::getMap());
    }

    /**
     * {@inheritDoc}
     */
    protected static function getIndexes()
    {
        return [
            new Index("idx_d_guid_id", ["d_guid_id" => 73]),
            new Index('idx_is_active', ['is_active'])
        ];
    }
}