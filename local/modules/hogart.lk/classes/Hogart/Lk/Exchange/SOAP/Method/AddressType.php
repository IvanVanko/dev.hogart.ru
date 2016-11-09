<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 07/10/2016
 * Time: 16:39
 */

namespace Hogart\Lk\Exchange\SOAP\Method;


use Bitrix\Main\Entity\UpdateResult;
use Hogart\Lk\Entity\AddressTypeTable;
use Hogart\Lk\Exchange\SOAP\AbstractMethod;
use Hogart\Lk\Exchange\SOAP\Request;
use Hogart\Lk\Exchange\SOAP\Response;
use Hogart\Lk\Exchange\SOAP\ResponseObject;

class AddressType extends AbstractMethod
{
    /**
     * @inheritDoc
     */
    function getName()
    {
        return "AddressType";
    }

    public function addressTypeGet()
    {
        return $this->client->getSoapClient()->AddressTypesGet(new Request());
    }

    public function updateAddressTypes()
    {
        $answer = new Response();
        $response = $this->addressTypeGet();
        foreach ($response->return->Address_Types as $address_Type) {
            $result = AddressTypeTable::createOrUpdateByField([
                "guid_id" => $address_Type->ID,
                "name" => $address_Type->Name,
                "code" => $address_Type->Code
            ], "guid_id");
            if ($result->getErrorCollection()->count()) {
                $error = $result->getErrorCollection()->current();
                $this->client->getLogger()->error($error->getMessage() . " (" . $error->getCode() . ")");
            } else {
                if ($result instanceof UpdateResult) {
                    $this->client->getLogger()->notice("Обновлена запись Тип Адреса");
                } else {
                    $this->client->getLogger()->notice("Добавлена запись Тип Адреса");
                }

                $answer->addResponse(new ResponseObject($address_Type->ID));
            }
        }
        return count($answer->Response);
    }
}
