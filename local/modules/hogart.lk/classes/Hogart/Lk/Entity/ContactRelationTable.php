<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 28/07/16
 * Time: 15:48
 */

namespace Hogart\Lk\Entity;


use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;

/**
 * Таблица связи контактов с друими таблицами
 * @package Hogart\Lk\Entity
 */
class ContactRelationTable extends AbstractEntityRelation
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return "h_contact_relation";
    }

    /**
     * {@inheritDoc}
     */
    public static function getMap()
    {
        return array_merge([
            new IntegerField("contact_id", ['primary' => true]),
            new ReferenceField("contact", "Hogart\\Lk\\Entity\\ContactTable", ["=this.contact_id" => "ref.id"]),
        ], parent::getMap());
    }

    /**
     * {@inheritDoc}
     */
    protected static function getIndexes()
    {
        return [
            new Index('idx_contact_relation', ['contact_id', 'owner_id']),
            new Index('idx_is_main', ['is_main']),
            new Index('idx_owner_type', ['owner_type'])
        ];
    }

    public static function getAccountContacts($account_id)
    {
        $contacts = self::getList([
            'filter' => [
                '=account.id' => $account_id
            ],
            'select' => [
                '' => 'contact',
            ]
        ])->fetchAll();

        foreach ($contacts as &$contact) {
            $contact['info'] = ContactInfoTable::getList([
                'filter' => [
                    '=contact.id' => $contact['id']
                ]
            ])->fetchAll();
        }

        return $contacts;
    }
}