<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 28/07/16
 * Time: 15:48
 */

namespace Hogart\Lk\Entity;


use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;

class ContactRelationTable extends AbstractEntityRelation
{
    /**
     * @inheritDoc
     */
    public static function getTableName()
    {
        return "h_contact_relation";
    }

    /**
     * @inheritDoc
     */
    public static function getMap()
    {
        return array_merge([
            new IntegerField("contact_id", ['primary' => true]),
            new ReferenceField("contact", "Hogart\\Lk\\Entity\\ContactTable", ["=this.contact_id" => "ref.id"]),
        ], parent::getMap());
    }

}