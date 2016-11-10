<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 01/08/16
 * Time: 16:09
 */

namespace Hogart\Lk\Entity;

use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Entity\FloatField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Entity\DateField;
use Bitrix\Main\DB\SqlExpression;
use Hogart\Lk\Field\GuidField;

class RTUItemTable extends AbstractEntity
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return "h_rtu_item";
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
            new StringField('d_guid_id'),  // составной GUID RTU_guid_id+'_'+Item_guid_id
            new IntegerField("rtu_id"),
            new ReferenceField("rtu", "RTUTable", ["=this.order_id" => "ref.id"]),

            new IntegerField("item_id"),
            new ReferenceField("item", "Bitrix\\Iblock\\ElementTable", ["=this.item_id" => "ref.ID", "=ref.IBLOCK_ID" => new SqlExpression('?i', CATALOG_IBLOCK_ID)]),

            new IntegerField("count"),
            new FloatField("cost"),
            new FloatField("discount"),
            new FloatField("discount_cost"),
            new FloatField("total"),
            new FloatField("total_vat"),
            new StringField("item_group")
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected static function getIndexes()
    {
        return [
            new Index('idx_d_guid_id', ['d_guid_id' => 73]),
            new Index('idx_rtu_item_entity_most', ['rtu_id', 'item_id']),
        ];
    }

    public static function getByRtu($rtu_id)
    {
        return self::getList([
            'filter' => [
                '=rtu_id' => $rtu_id
            ]
        ])->fetchAll();
    }

    public static function deleteByRTUId($id)
    {
        $items = self::getList([
            'filter' => [
                '=rtu_id' => intval($id)
            ]
        ]);
        while (($rtuItem = $items->fetch())) {
            self::delete($rtuItem['id']);
        }
        return true;
    }
}