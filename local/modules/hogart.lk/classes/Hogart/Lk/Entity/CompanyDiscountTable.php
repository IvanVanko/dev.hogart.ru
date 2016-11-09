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
    public static function preparePricesByContract($contract_id = 0, $prices = [])
    {
        // ID номенклатуры
        $id = array_keys($prices);
        $discounts = [];
        if ($contract_id > 0) {
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
        }
        foreach ($prices as $id => &$price) {
            $new_price = round($price * (100 - floatval($discounts[$id]['discount'])) / 100, 2);
            $price = [
                'discount' =>  floatval($discounts[$id]['discount']),
                'price' => $new_price,
                'discount_amount' => floatval($price - $new_price)
            ];
        }
        return $prices;
    }

    public static function prepareFrontByAccount($account_id, $prices)
    {
        $account = AccountTable::getAccountById($account_id);
        $contract_id = $account['main_contract_id'];
        if (empty($contract_id)) {
            $companies = AccountCompanyRelationTable::getByAccountId($account['id']);
            if (count($companies) == 1) {
                $current_company = &reset($companies);
            } else {
                foreach ($companies as &$company) {
                    if ($company['is_favorite']) {
                        $current_company = &$company;
                        break;
                    }
                }
            }
            if (!empty($current_company)) {
                $contract = reset(ContractTable::getByCompanyId($current_company['id']));
                $contract_id = $contract['id'];
            }
        }
        return self::preparePricesByContract((int)$contract_id, $prices);
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