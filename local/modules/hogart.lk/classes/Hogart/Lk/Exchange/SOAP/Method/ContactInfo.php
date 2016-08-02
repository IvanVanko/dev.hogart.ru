<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 02/08/16
 * Time: 23:33
 */

namespace Hogart\Lk\Exchange\SOAP\Method;


use Hogart\Lk\Exchange\SOAP\AbstractMethod;

class ContactInfo extends AbstractMethod
{
    /**
     * @inheritDoc
     */
    function getName()
    {
        return "ContactInfo";
    }

    public function getContactsInfo()
    {
        return $this->client->getSoapClient()->ContactInfoGet(new Request());
    }

    
}