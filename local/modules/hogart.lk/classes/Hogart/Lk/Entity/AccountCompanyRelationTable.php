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

class AccountCompanyRelationTable extends AbstractEntity
{
    /**
     * @inheritDoc
     */
    public static function getTableName()
    {
        return "h_account_company_relation";
    }

    /**
     * @inheritDoc
     */
    public static function getMap()
    {
        return [
            new IntegerField("account_id", ['primary' => true]),
            new ReferenceField("account", "AccountTable", ["=this.account_id" => "ref.id"]),
            new GuidField("company_id", ['primary' => true]),
            new ReferenceField("company", "CompanyTable", ["=this.company_id" => "ref.id"]),
        ];
    }
}