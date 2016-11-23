<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 23/11/2016
 * Time: 14:56
 */

namespace Hogart\Lk\Entity;


use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
use Hogart\Lk\Field\GuidField;

class OrderItemEditRelTable extends AbstractEntity
{
    /**
     * @inheritDoc
     */
    public static function getTableName()
    {
        return "h_order_item_edit_rel";
    }

    /**
     * @inheritDoc
     */
    public static function getMap()
    {
        return [
            new GuidField("order_edit_item_guid", ["primary" => true]),
            new ReferenceField("order_edit_item", __NAMESPACE__ . "\\OrderItemEditTable", ["=this.order_edit_item_guid" => "ref.guid_id"]),
            new IntegerField("order_item_id", ["primary" => true]),
            new ReferenceField("order_item", __NAMESPACE__ . "\\OrderItemTable", ["=this.order_item_id" => "ref.id"]),
        ];
    }
}
