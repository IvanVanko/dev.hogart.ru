<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 02/08/16
 * Time: 12:47
 */

namespace Hogart\Lk\Exchange\SOAP\Method;


use Hogart\Lk\Exchange\SOAP\Client;

class Response extends Request
{
    /** @var  AbstractResponseObject[] */
    public $Response = [];

    public function addResponse(AbstractResponseObject $responseObject)
    {
        $this->Response[] = $responseObject;

        if($responseObject->Error !== null)
            Client::getInstance()->getLogger()->error($responseObject->ErrorText . " (" . $responseObject->Error . ")");

        return $this;
    }
}
