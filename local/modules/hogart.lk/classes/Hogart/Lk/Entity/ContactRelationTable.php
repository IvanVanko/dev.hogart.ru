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
use Bitrix\Main\Entity\StringField;

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
            new ReferenceField("contact", __NAMESPACE__ . "\\ContactTable", ["=this.contact_id" => "ref.id"]),
            new StringField("post")
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

    /**
     * @param int $owner_id
     * @param int $owner_type
     * @return array
     */
    public static function getContactsByOwner($owner_id, $owner_type)
    {
        $contacts = self::getByOwner($owner_id, $owner_type, [], [
            '*',
            'post',
            '' => 'contact',
        ]);

        foreach ($contacts as &$contact) {
            $contact['info'] = array_reduce(ContactInfoTable::getList([
                'filter' => [
                    '=contact.id' => $contact['id'],
                ]
            ])->fetchAll(), function ($result, $item) { $result[$item['info_type']][] = $item; return $result; }, []);
        }

        return $contacts;
    }

    /**
     * @param $account_id
     * @return array
     */
    public static function getAccountContacts($account_id)
    {
        return self::getContactsByOwner($account_id, self::OWNER_TYPE_ACCOUNT);
    }
}