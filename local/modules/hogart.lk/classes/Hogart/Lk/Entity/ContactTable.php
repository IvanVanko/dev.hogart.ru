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
use Hogart\Lk\Exchange\RabbitMQ\Exchange\ContactExchange;
use Hogart\Lk\Exchange\SOAP\Request\Contact;
use Hogart\Lk\Field\GuidField;
use Hogart\Lk\Field\HashSum;

/**
 * Таблица Контактов
 * @package Hogart\Lk\Entity
 */
class ContactTable extends AbstractEntity implements IExchangeable
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
            new Index("idx_hash", ["hash" => 40]),
            new Index("idx_guid_id", ["guid_id" => 36]),
            new Index('idx_is_active', ['is_active']),
        ];
    }

    public static function getFio($contact, $prefix = '')
    {
        return implode(' ', [
            $contact[$prefix . 'last_name'],
            $contact[$prefix . 'name'],
            $contact[$prefix . 'middle_name']
        ]);
    }


    public static function putTo1c($primary)
    {
        $contacts = self::getList([
            'filter' => [
                '=id' => $primary
            ],
            'select' => [
                '*',
                'a_' => __NAMESPACE__ . '\ContactRelationTable:contact.account'
            ]
        ])->fetchAll();
        self::publishToRabbit(new ContactExchange(), new Contact($contacts));
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

    public static function onAfterAdd(Event $event)
    {
        self::putTo1c($event->getParameter('id'));
    }

    public static function onAfterUpdate(Event $event)
    {
        self::putTo1c($event->getParameter('id')['id']);
    }
}
