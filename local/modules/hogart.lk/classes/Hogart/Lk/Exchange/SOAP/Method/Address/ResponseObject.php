<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 03/08/16
 * Time: 15:43
 */

namespace Hogart\Lk\Exchange\SOAP\Method\Address;


use Hogart\Lk\Exchange\SOAP\AbstractResponseObject;
use Hogart\Lk\Exchange\SOAP\MethodException;

class ResponseObject extends AbstractResponseObject
{
    /** @var  string */
    public $ID_Owner;
    /** @var  integer */
    public $Owner_Type;
    /** @var  string */
    public $ID_Address_Type;

    /**
     * {@inheritDoc}
     */
    public function __construct($ID_Owner, $Owner_Type, $ID_Address_Type, MethodException $e = null)
    {
        $this->ID_Owner = $ID_Owner;
        $this->Owner_Type = $Owner_Type;
        $this->ID_Address_Type = $ID_Address_Type;
        parent::__construct($e);
    }
}
