<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 02/08/16
 * Time: 12:51
 */

namespace Hogart\Lk\Exchange\SOAP\Method;


class ResponseObject
{
    /** @var  string|integer */
    public $ID;
    /** @var string */
    public $Error;
    /** @var  string */
    public $ErrorText;

    /**
     * ResponseObject constructor.
     * @param string $ID
     * @param MethodException $Error
     */
    public function __construct($ID, MethodException $e = null)
    {
        $this->ID = $ID;
        if (null !== $e) {
            $this->Error = $e->getCode();
            $this->ErrorText = $e->getMessage();
        }
    }
}
