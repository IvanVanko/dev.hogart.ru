<?php
/**
 * By: Ivan Kiselev aka shaqito[at]gmail.com
 * Via: PhpStorm.
 * At: 04.08.2016 14:50
 */

namespace Hogart\Lk\Entity;

use Bitrix\Main\Entity\BooleanField;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
use Hogart\Lk\Field\GuidField;

/**
 * Таблица связи Аккаунта и Компаний клиента
 * @package Hogart\Lk\Entity
 */
class AccountCompanyRelationTable extends AbstractEntity
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return "h_account_company_relation";
    }

    /**
     * {@inheritDoc}
     */
    public static function getMap()
    {
        return [
            new IntegerField("account_id", ['primary' => true]),
            new ReferenceField("account", "Hogart\\Lk\\Entity\\AccountTable", ["=this.account_id" => "ref.id"]),
            new IntegerField("company_id", ['primary' => true]),
            new ReferenceField("company", "Hogart\\Lk\\Entity\\CompanyTable", ["=this.company_id" => "ref.id"]),
            new BooleanField("is_favorite")
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected static function getIndexes()
    {
        return [
            new Index("idx_account_company_relation", ['account_id', 'company_id']),
        ];
    }

    /**
     * Получить все компании пользователя
     * @param int $account_id
     * @param bool $is_active
     * @return array
     */
    public static function getByAccountId($account_id)
    {
        return array_reduce(self::getList([
            'filter' => [
                '=account_id' => $account_id
            ],
            'select' => [
                '' => 'company',
                'is_favorite' => 'is_favorite'
            ]
        ])->fetchAll(), function ($result, $item) { $result[$item['id']] = $item; return $result; }, []);
    }

    /**
     * Полчить определенную компанию пользователя
     * @param int $company_id
     * @param int $account_id
     * @return array
     */
    public static function getCurrentCompany($company_id, $account_id)
    {
        return self::getList([
            'filter' => [
                '=account_id' => $account_id,
                '=company_id' => $company_id,
                '=company.is_active' => true
            ],
            'select' => [
                '' => 'company',
                'is_favorite' => 'is_favorite'
            ]
        ])->fetch();
    }
    /**
     * Полчить определенную "любимую" компанию пользователя
     * @param int $account_id
     * @return array
     */
    public static function getFavoriteCompany($account_id)
    {
        return self::getList([
            'filter' => [
                '=account_id' => $account_id,
                '=is_favorite' => true,
                '=company.is_active' => true
            ],
            'select' => [
                'COMPANY_' => 'company'
            ]
        ])->fetchAll();
    }

    public static function toggleFavorite($account_id, $company_id)
    {
        $current = self::getList([
            'filter' => [
                '=account_id' => $account_id,
                '=company_id' => $company_id,
                '=company.is_active' => true
            ],
        ])->fetch();
        if ($current['is_favorite'] == false) {
            self::createOrUpdateByField(['account_id'=>$account_id, 'is_favorite'=>false], 'account_id');
            self::createOrUpdateByField(['is_favorite'=>true], ['account_id'=>$account_id, 'company_id'=>$company_id]);
        }else{
            self::createOrUpdateByField(['is_favorite'=>false], ['account_id'=>$account_id, 'company_id'=>$company_id]);
        }
    }
}