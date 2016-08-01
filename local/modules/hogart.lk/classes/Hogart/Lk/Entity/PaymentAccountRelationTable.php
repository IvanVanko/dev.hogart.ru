<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 29/07/16
 * Time: 16:39
 */

namespace Hogart\Lk\Entity;


use Bitrix\Main\Entity\BooleanField;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;

class PaymentAccountRelationTable extends AbstractEntityRelation
{
    /**
     * @inheritDoc
     */
    public static function getTableName()
    {
        return "h_payment_account_relation";
    }

    /**
     * @inheritDoc
     */
    public static function getMap()
    {
        return array_merge([
            new IntegerField("payment_account_id", ['primary' => true]),
            new ReferenceField("payment_account", "PaymentAccountTable", ["=this.payment_account_id" => "ref.id"]),
        ], parent::getMap());
    }
}
