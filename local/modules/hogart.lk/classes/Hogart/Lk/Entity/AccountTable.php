<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 28/07/16
 * Time: 15:44
 */

namespace Hogart\Lk\Entity;


use Bitrix\Main\Entity\BooleanField;
use Bitrix\Main\Entity\Event;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
use Hogart\Lk\Field\GuidField;

/**
 * Таблица Аккаунт
 * @package Hogart\Lk\Entity
 */
class AccountTable extends AbstractEntity
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return "h_account";
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

            new GuidField("user_guid_id"),

            new IntegerField("user_id"),
            new ReferenceField("user", "Bitrix\\Main\\UserTable", ["=this.user_id" => "ref.ID"]),

            new IntegerField("contact_id"),
            new ReferenceField("contact", __NAMESPACE__ . "\\ContactTable", ["=this.contact_id" => "ref.id"]),

            new IntegerField("main_manager_id"),
            new ReferenceField("main_manager", __NAMESPACE__ . "\\StaffTable", ["=this.main_manager_id" => "ref.id"]),

            new IntegerField("main_store_id"),
            new ReferenceField("main_store", __NAMESPACE__ . "\\StoreTable", ["=this.main_store_id" => "ref.ID"]),

            new IntegerField("head_account_id"),
            new ReferenceField("head_account", __NAMESPACE__ . "\\AccountTable", ["=this.head_account_id" => "ref.id"]),

            new IntegerField("main_contract_id"),
            new ReferenceField("main_contract", __NAMESPACE__ . "\\ContractTable", ["=this.main_contract_id" => "ref.id"]),

            new BooleanField("is_promo_accesss"),
            new BooleanField("is_active")

        ];
    }

    /**
     * {@inheritDoc}
     */
    protected static function getIndexes()
    {
        return [
            new Index("idx_user_guid_id", ["user_guid_id" => 36]),
            new Index("idx_account_entity_most", ['user_id', 'contact_id', 'main_manager_id', 'main_store_id', 'head_account_id', 'main_contract_id']),
            new Index("idx_is_active", ["is_active"]),
            new Index("idx_is_promo_accesss", ["is_promo_accesss"]),
        ];
    }

    /**
     * @param int $ID
     * @param bool $is_active
     * @return array|false
     * @throws \Bitrix\Main\ArgumentException
     */
    public static function getAccountByUserID($ID, $is_active = true)
    {
        return self::getList([
            'filter' => [
                '=user_id' => $ID,
                '=is_active' => $is_active,
            ],
            'select' => [
                '*',
                'user_' => 'user',
                'manager_' => 'main_manager'
            ]
        ])->fetch();
    }

    /**
     * @param $ID
     * @param bool $is_active
     * @return array|false
     * @throws \Bitrix\Main\ArgumentException
     */
    public static function getAccountById($ID, $is_active = true)
    {
        return self::getList([
            'filter' => [
                '=id' => $ID,
                '=is_active' => $is_active,
            ],
            'select' => [
                '*',
                'user_' => 'user'
            ]
        ])->fetch();
    }

    /**
     * Получение подчиненных аккаунтов
     * 
     * @param $ID
     * @param bool $is_active
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     */
    public static function getSubAccounts($ID, $is_active = true)
    {
        return self::getList([
            'filter' => [
                '=head_account_id' => $ID,
                '=is_active' => $is_active,
            ],
            'select' => [
                '*',
                'user_' => 'user'
            ]
        ])->fetchAll();
    }

    /**
     * @param $email
     */
    public static function sendNewAccountPassword($email)
    {
        /** @global \CMain $APPLICATION */
        global $DB;
        $strSql =
            "SELECT ID, LID, ACTIVE, CONFIRM_CODE, LOGIN, EMAIL, NAME, LAST_NAME ".
            "FROM b_user u ".
            "WHERE EMAIL='".$DB->ForSQL($email)."' ".
            "	AND (ACTIVE='Y' OR NOT(CONFIRM_CODE IS NULL OR CONFIRM_CODE='')) ".
            "	AND (EXTERNAL_AUTH_ID IS NULL OR EXTERNAL_AUTH_ID='') ";
        $res = $DB->Query($strSql);
        $arUser = $res->Fetch();
        $site_id = \CSite::GetDefSite($arUser["LID"]);
        \CUser::SendUserInfo($arUser["ID"], $site_id, '', true, 'ACCOUNT_NEW_USER');
    }

    /**
     * @param $account_id
     * @return bool
     * @throws \Bitrix\Main\ArgumentException
     */
    public static function isGeneralAccount($account_id)
    {
        $account = self::getList([
            'filter' => [
                '=id' => $account_id,
                '=head_account_id' => 0
            ]
        ])->fetch();
        return !empty($account);
    }
}
