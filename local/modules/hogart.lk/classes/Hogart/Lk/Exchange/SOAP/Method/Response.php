<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 02/08/16
 * Time: 12:47
 */

namespace Hogart\Lk\Exchange\SOAP\Method;


class Response extends Request
{
    /** @var  AbstractResponseObject[] */
    public $Response = [];

    public function addResponse(AbstractResponseObject $responseObject)
    {
        $this->Response[] = $responseObject;

        return $this;
    }
}
