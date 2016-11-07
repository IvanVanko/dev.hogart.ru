<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 01/09/16
 * Time: 18:19
 */

namespace Hogart\Lk\Field;


use Bitrix\Main\Entity\StringField;

class HashSum extends StringField
{
    public function __construct($name, $parameters = array())
    {
        parent::__construct($name, array_merge([
            'size' => 40,
            'unique' => true
        ], $parameters));
    }
}