<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 01/08/16
 * Time: 16:10
 */

namespace Hogart\Lk\Entity;


class OrderPaymentTable extends AbstractEntity
{
    /**
     * @inheritDoc
     */
    public static function getTableName()
    {
        return "h_order_payment";
    }

    /**
     * @inheritDoc
     */
    public static function getMap()
    {
        return [
            
        ];
    }

}