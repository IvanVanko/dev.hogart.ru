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
            new ReferenceField("company", __NAMESPACE__ . "\\CompanyTable", ["=this.company_id" => "ref.id"]),
            new IntegerField("item_id"),
            new ReferenceField("item", "Bitrix\\Iblock\\ElementTable", ["=this.item_id" => "ref.ID", "=ref.IBLOCK_ID" => new SqlExpression('?i', CATALOG_IBLOCK_ID)]),
            new FloatField("discount")
        ];
    }

    /**
     * @param $contract_id
     * @param array $prices
     * @return array
     */
    public static function preparePricesByContract($contract_id, $prices = [])
    {
        // ID номенклатуры
        $id = array_keys($prices);
        $discounts = array_reduce(self::getList([
            'filter' => [
                '=item_id' => $id,
                '=company.Hogart\Lk\Entity\ContractTable:company.id' => $contract_id
            ],
            'select' => [
                'item_id',
                'discount'
            ]
        ])->fetchAll(), function ($result, $item) { $result[$item['item_id']]['discount'] = $item['discount']; return $result; }, []);
        foreach ($prices as $id => &$price) {
            $new_price = round($price * (100 - $discounts[$id]['discount']) / 100, 2);
            $price = [
                'discount' => $discounts[$id]['discount'],
                'price' => $new_price,
                'discount_amount' => $price - $new_price
            ];
        }
        return $prices;
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