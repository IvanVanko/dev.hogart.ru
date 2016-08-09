<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 29/07/16
 * Time: 16:03
 */

namespace Hogart\Lk\Entity;


use Bitrix\Main\DB\SqlExpression;
use Bitrix\Main\Entity\FloatField;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;

/**
 * Таблица Скидок компании на товары
 * @package Hogart\Lk\Entity
 */
class CompanyDiscountTable extends AbstractEntity
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return "h_company_discount";
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
            new IntegerField("company_id"),
            new ReferenceField("company", "CompanyTable", ["=this.company_id" => "ref.id"]),
            new IntegerField("item_id"),
            new ReferenceField("item", "Bitrix\\Iblock\\ElementTable", ["=this.item_id" => "ref.ID", "=ref.IBLOCK.ID" => new SqlExpression('?i', CATALOG_IBLOCK_ID)]),
            new FloatField("discount")
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected static function getIndexes()
    {
        return [
            new Index("idx_company_discount_entity_most", ['company_id', 'item_id']),
        ];
    }
}