<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 06/10/2016
 * Time: 18:41
 */

namespace Hogart\Lk\Exchange\SOAP\Method;


use Hogart\Lk\Exchange\SOAP\AbstractMethod;
use Hogart\Lk\Exchange\SOAP\AbstractPutRequest;

class OrderRTU extends AbstractMethod
{
    /**
     * @inheritDoc
     */
    function getName()
    {
        return "OrderRTU";
    }

    public function orderRTUPut(AbstractPutRequest $request)
    {
        var_dump($request->__toRequest());
        exit;
        $response = $this->client->getSoapClient()->OrderRTUPut($request->__toRequest());
    }
}
