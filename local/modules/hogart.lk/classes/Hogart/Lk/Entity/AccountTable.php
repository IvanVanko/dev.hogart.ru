<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 28/07/16
 * Time: 15:44
 */

namespace Hogart\Lk\Entity;


use Bitrix\Main\Entity\BooleanField;
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
            new ReferenceField("contact", "\\ContactTable", ["=this.contact_id" => "ref.id"]),

            new IntegerField("main_manager_id"),
            new ReferenceField("main_manager", "\\StaffTable", ["=this.main_manager_id" => "ref.id"]),

            new IntegerField("main_store_id"),
            new ReferenceField("main_store", "Bitrix\\Catalog\\StoreTable", ["=this.main_store_id" => "ref.ID"]),

            new IntegerField("head_account_id"),
            new ReferenceField("head_account", "\\AccountTable", ["=this.head_account_id" => "ref.id"]),

            new IntegerField("main_contract_id"),
            new ReferenceField("main_contract", "\\ContractTable", ["=this.main_contract_id" => "ref.id"]),

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
}
