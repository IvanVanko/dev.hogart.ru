<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 28/07/16
 * Time: 15:51
 */

namespace Hogart\Lk\Entity;


use Bitrix\Main\DB\SqlExpression;
use Bitrix\Main\Entity\EnumField;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;

class AbstractEntityRelation extends AbstractEntity
{
    const OWNER_TYPE_ACCOUNT = 1;
    const OWNER_TYPE_CLIENT_COMPANY = 2;
    const OWNER_TYPE_STAFF = 3;
    const OWNER_TYPE_HOGART_COMPANY = 4;
    const OWNER_TYPE_STORE = 5;
    const OWNER_TYPE_CONTACT = 6;
    
    

    /**
     * @inheritDoc
     */
    public static function getMap()
    {
        return [
            new IntegerField("owner_id", ['primary' => true]),
            new ReferenceField("account", "Bitrix\\Main\\UserTable", ["=this.owner_id" => "ref.ID", "=this.owner_type" => new SqlExpression('?i', self::OWNER_TYPE_ACCOUNT)]),
            new ReferenceField("client_company", "ClientCompanyTable", ["=this.owner_id" => "ref.id", "=this.owner_type" => new SqlExpression('?i', self::OWNER_TYPE_CLIENT_COMPANY)]),
            new ReferenceField("staff", "StaffTable", ["=this.owner_id" => "ref.id", "=this.owner_type" => new SqlExpression('?i', self::OWNER_TYPE_STAFF)]),
            new ReferenceField("hogart_company", "HogartCompanyTable", ["=this.owner_id" => "ref.id", "=this.owner_type" => new SqlExpression('?i', self::OWNER_TYPE_HOGART_COMPANY)]),
            new ReferenceField("store", "Bitrix\\Catalog\\StoreTable", ["=this.owner_id" => "ref.ID", "=this.owner_type" => new SqlExpression('?i', self::OWNER_TYPE_STORE)]),
            new ReferenceField("contact", "ContactTable", ["=this.owner_id" => "ref.id", "=this.owner_type" => new SqlExpression('?i', self::OWNER_TYPE_CONTACT)]),
            new EnumField("owner_type", [
                'primary' => true,
                'values' => [
                    self::OWNER_TYPE_ACCOUNT,
                    self::OWNER_TYPE_CLIENT_COMPANY,
                    self::OWNER_TYPE_STAFF,
                    self::OWNER_TYPE_HOGART_COMPANY,
                    self::OWNER_TYPE_STORE,
                    self::OWNER_TYPE_CONTACT,
                ]
            ])
        ];
    }
}
