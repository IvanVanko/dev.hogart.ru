<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 02/08/16
 * Time: 12:51
 */

namespace Hogart\Lk\Exchange\SOAP\Method;


class AnswerObject
{
    /** @var  string|integer */
    public $ID;
    /** @var null|MethodException */
    public $Error;

    /**
     * AnswerObject constructor.
     * @param $ID
     * @param $Error
     */
    public function __construct($ID, $Error = null)
    {
        $this->ID = $ID;
        $this->Error = $Error;
    }
}
