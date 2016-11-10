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
use Bitrix\Main\Entity\Event;
use Bitrix\Main\Entity\EventResult;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ScalarField;
use Bitrix\Main\Entity\StringField;
use Hogart\Lk\Field\GuidField;
use Ramsey\Uuid\Uuid;

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
            new GuidField("guid_id", ['primary' => true]),
            new StringField("d_guid_id"),
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
            new StringField("value", [
                'primary' => true
            ]),
            new BooleanField("is_active")
        ], parent::getMap());
    }

    /**
     * {@inheritDoc}
     */
    protected static function getIndexes()
    {
        return [
            new Index("idx_guid_id", ["guid_id" => 36]),
            new Index("idx_d_guid_id", ["d_guid_id" => 73]),
            new Index('idx_is_active', ['is_active'])
        ];
    }

    public static function clearPhone($phone)
    {
        return preg_replace("%[^\\d]%", "", $phone);
    }

    public static function formatPhone($phone)
    {
        return trim(preg_replace("%(\d{1})(\d{3})(\d{3})(\d{2})(\d{2})(\d*)%", "+\\1 (\\2) \\3-\\4-\\5 \\6", trim($phone)));
    }

    public static function getUUID($owner_id, $owner_type, $info_type, $phone_kind, $value)
    {
        return Uuid::uuid5(Uuid::NAMESPACE_OID, implode('|', [
            $owner_id, $owner_type, $info_type, $phone_kind, $value
        ]))->toString();
    }

    public static function onBeforeAdd(Event $event)
    {
        $result = new EventResult();
        $fields = $event->getParameter('fields');
        // set fields with default values
        foreach (static::getEntity()->getFields() as $field) {
            if ($field instanceof ScalarField && !array_key_exists($field->getName(), $fields)) {
                $defaultValue = $field->getDefaultValue();

                if ($defaultValue !== null) {
                    $fields[$field->getName()] = $field->getDefaultValue();
                }
            }
        }

        $value = ($fields['info_type'] == self::TYPE_PHONE ? self::clearPhone($fields['value']) : $fields['value']);
        $result->modifyFields([
            'guid_id' => self::getUUID($fields['owner_id'],
                $fields['owner_type'],
                $fields['info_type'],
                $fields['phone_kind'],
                $value
            ),
            'value' => $value
        ]);
        return $result;
    }
}