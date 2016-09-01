<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 28/07/16
 * Time: 15:44
 */

namespace Hogart\Lk\Entity;


use Bitrix\Main\Entity\BooleanField;
use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\EntityError;
use Bitrix\Main\Entity\Event;
use Bitrix\Main\Entity\EventResult;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Hogart\Lk\Field\GuidField;
use Hogart\Lk\Field\HashSum;

/**
 * Таблица Контактов
 * @package Hogart\Lk\Entity
 */
class ContactTable extends AbstractEntity
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return "h_contact";
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
            new HashSum("hash"),
            new StringField("name"),
            new StringField("last_name"),
            new StringField("middle_name"),
            new BooleanField("is_active", [
                'default_value' => true
            ])
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected static function getIndexes()
    {
        return [
            new Index("idx_guid_id", ["guid_id" => 36]),
            new Index('idx_is_active', ['is_active']),
        ];
    }

    public static function onBeforeAdd(Event $event)
    {
        $fields = $event->getParameter("fields");
        $result = new EventResult();
        $result->modifyFields([
            'hash' => $hash = sha1(implode("|", [
                mb_strtolower($fields['name']),
                mb_strtolower($fields['last_name']),
                mb_strtolower($fields['middle_name']),
            ]))
        ]);
        return $result;
    }

    public static function onBeforeUpdate(Event $event)
    {
        return self::onBeforeAdd($event);
    }
}
