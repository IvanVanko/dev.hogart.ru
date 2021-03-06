<?php
/**
 * By: Ivan Kiselev aka shaqito[at]gmail.com
 * Via: PhpStorm.
 * At: 04.08.2016 14:50
 */

namespace Hogart\Lk\Entity;

use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
use Hogart\Lk\Field\GuidField;

/**
 * Таблица связи Аккаунта и Складов клиента
 * @package Hogart\Lk\Entity
 */
class AccountStoreRelationTable extends AbstractEntity
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return "h_account_store_relation";
    }

    /**
     * {@inheritDoc}
     */
    public static function getMap()
    {
        return [
            new IntegerField("account_id", ['primary' => true]),
            new ReferenceField("account", __NAMESPACE__ . "\\AccountTable", ["=this.account_id" => "ref.id"]),
            new GuidField("store_guid", ['primary' => true]),
            new ReferenceField("store", __NAMESPACE__ . "\\StoreTable", ["=this.store_guid" => "ref.XML_ID"]),
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected static function getIndexes()
    {
        return [
            new Index("idx_account_relation", ['account_id', 'store_guid' => 36]),
        ];
    }

    /**
     * @param $account_id
     * @param $store_guid
     * @return bool
     */
    public static function isAccountStore($account_id, $store_guid)
    {
        $row = self::getRow([
            'filter' => [
                '=account_id' => $account_id,
                '=store_guid' => $store_guid
            ]
        ]);

        return !empty($row);
    }

    /**
     * @param int $account_id
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     */
    public static function getByAccountId($account_id)
    {
        return array_reduce(self::getList([
            'filter' => [
                '=account_id' => $account_id
            ],
            'select' => [
                '' => 'store',
                'a_' => 'account'
            ]
        ])->fetchAll(), function ($result, $store) {
            $result[$store['ID']] = $store;
            return $result;
        }, []);
    }
}