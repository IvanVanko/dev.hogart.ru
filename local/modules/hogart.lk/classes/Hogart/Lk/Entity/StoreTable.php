<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 27/08/16
 * Time: 22:56
 */

namespace Hogart\Lk\Entity;

use Bitrix\Catalog\StoreTable as BaseClass;

class StoreTable extends BaseClass
{
    public static function getUfId()
    {
        return "CAT_STORE";
    }

}