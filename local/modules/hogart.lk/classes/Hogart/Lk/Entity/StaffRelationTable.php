<?php
/**
 * By: Ivan Kiselev aka shaqito[at]gmail.com
 * Via: PhpStorm.
 * At: 04.08.2016 14:50
 */

namespace Hogart\Lk\Entity;

use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;

class StaffRelationTable extends AbstractEntityRelation
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return "h_staff_relation";
    }

    /**
     * {@inheritDoc}
     */
    public static function getMap()
    {
        return array_merge([
            new IntegerField("staff_id", ['primary' => true]),
            new ReferenceField("staff", __NAMESPACE__ . "\\StaffTable", ["=this.staff_id" => "ref.id"]),
        ], parent::getMap());
    }

    /**
     * {@inheritDoc}
     */
    protected static function getIndexes()
    {
        return [
            new Index('idx_staff_relation', ['staff_id', 'owner_id']),
            new Index('idx_is_main', ['is_main']),
            new Index('idx_owner_type', ['owner_type'])
        ];
    }

    /**
     * @param $account_id
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     */
    public static function getManagersByAccountId($account_id)
    {
        return array_map(function ($manager) {
            $manager['info'] = array_reduce(ContactInfoTable::getList([
                'filter' => [
                    '=staff.id' => $manager['id']
                ]
            ])->fetchAll(), function ($result, $item) { $result[$item['info_type']] = $item; return $result; }, []);
            return $manager;
        }, self::getList([
            'filter' => [
                '=account.id' => $account_id
            ],
            'select' => [
                'manager_' => 'staff'
            ]
        ])->fetchAll());
    }
}