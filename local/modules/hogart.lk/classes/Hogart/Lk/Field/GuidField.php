<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 27/07/16
 * Time: 22:20
 */

namespace Hogart\Lk\Field;

use Bitrix\Main\Entity\StringField;

class GuidField extends StringField
{
    public function __construct($name, $parameters = array())
    {
        parent::__construct($name, array_merge([
            'size' => 32,
            'format' => '%[0-9a-z]{8}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{12}%',
            'unique' => true
        ], $parameters));
    }
}
