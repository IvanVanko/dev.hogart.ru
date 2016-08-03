<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 02/08/16
 * Time: 12:51
 */

namespace Hogart\Lk\Exchange\SOAP\Method;


class ResponseObject extends AbstractResponseObject
{
    /** @var  string|integer */
    public $ID;

    /**
     * @inheritDoc
     */
    public function __construct($ID, MethodException $e = null)
    {
        $this->ID = $ID;
        parent::__construct($e);
    }
}
