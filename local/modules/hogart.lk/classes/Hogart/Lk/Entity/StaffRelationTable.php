<?php
/**
 * By: Ivan Kiselev aka shaqito[at]gmail.com
 * Via: PhpStorm.
 * At: 04.08.2016 14:50
 */

namespace Hogart\Lk\Entity;

use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;

class StaffRelationTable extends AbstractEntityRelation
{
    /**
     * @inheritDoc
     */
    public static function getTableName()
    {
        return "h_staff_relation";
    }

    /**
     * @inheritDoc
     */
    public static function getMap()
    {
        return array_merge([
            new IntegerField("staff_id", ['primary' => true]),
            new ReferenceField("staff", "StaffTable", ["=this.staff_id" => "ref.id"]),
        ], parent::getMap());
    }
}