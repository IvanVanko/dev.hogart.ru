<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 02/08/16
 * Time: 17:12
 */

namespace Hogart\Lk\Exchange\SOAP\Method;


use Bitrix\Main\Entity\UpdateResult;
use Hogart\Lk\Entity\AbstractEntity;
use Hogart\Lk\Entity\AddressTable;
use Hogart\Lk\Entity\AddressTypeTable;
use Hogart\Lk\Exchange\SOAP\AbstractMethod;
use Hogart\Lk\Exchange\SOAP\AbstractPutRequest;
use Hogart\Lk\Exchange\SOAP\Method\Address\ResponseObject;
use Hogart\Lk\Exchange\SOAP\MethodException;
use Hogart\Lk\Exchange\SOAP\Request;
use Hogart\Lk\Exchange\SOAP\Response;

class Address extends AbstractMethod
{
    /**
     * {@inheritDoc}
     */
    function getName()
    {
        return "Address";
    }

    public function getAddresses()
    {
        return $this->client->getSoapClient()->AddressesGet(new Request());
    }

    public function addressAnswer(Response $response)
    {
        if (count($response->Response) && $this->is_answer) {
            return $this->client->getSoapClient()->AddressesAnswer($response);
        }
    }

    public function addressPut(AbstractPutRequest $request)
    {
        var_dump($request->__toRequest());
        exit;
        $response = $this->client->getSoapClient()->AddressesPut($request->__toRequest());
        return $response;
    }

    public function updateAddresses()
    {
        $answer = new Response();
        $result = $this->getAddresses();
        $types = [];
        foreach ($result->return->Address_Types as $address_type) {
            $data = [
                'guid_id' => $address_type->ID,
                'name' => $address_type->Name
            ];
            $res = AddressTypeTable::createOrUpdateByField($data, 'guid_id');
            $types[$address_type->ID] = $res->getPrimary()['guid_id'];
        }
        $this->client->getLogger()->debug("AddressesGet: получено " . count($result->return->Address) . " записей");
        foreach ($result->return->Address as $address) {
            /** @var AbstractEntity $relTable */
            $relTable = AddressTable::$types[$address->Adr_Owner_Type]['table'];
            $owner = $relTable::getByField(AddressTable::$types[$address->Adr_Owner_Type]['rel'], $address->Adr_ID_Owner);

            $owner_id = $owner[AddressTable::$types[$address->Adr_Owner_Type]['rel_id']];
            $data = [
                'owner_id' => $owner_id,
                'owner_type' => $address->Adr_Owner_Type,
                'type_id' => $types[$address->Adr_ID_Address_Type],
                'postal_code' => (string)$address->Adr_ID_Index,
                'region' => (string)$address->Adr_Region,
                'city' => (string)$address->Adr_City,
                'street' => (string)$address->Adr_Street,
                'house' => (string)$address->Adr_House,
                'building' => (string)$address->Adr_Building,
                'flat' => (string)$address->Adr_Flat,
                'kladr_code' => (string)$address->Adr_Kod_KLADR,
                'is_active' => !$address->deletion_mark
            ];
            $result = AddressTable::replace($data);
            $responseObject = new ResponseObject($address->Adr_ID_Owner, $address->Adr_Owner_Type, $address->Adr_ID_Address_Type);
            if ($result->getErrorCollection()->count()) {
                $error = $result->getErrorCollection()->current();
                $this->client->getLogger()->error($error->getMessage() . " (" . $error->getCode() . ")");
                $responseObject->setError(new MethodException(MethodException::ERROR_BITRIX, [$error->getMessage(), $error->getCode()]));
            } else {
                if ($result instanceof UpdateResult) {
                    $this->client->getLogger()->notice("Обновлена запись Адреса");
                } else {
                    $this->client->getLogger()->notice("Добавлена запись Адреса");
                }
            }
            $answer->addResponse($responseObject);
        }
        $this->addressAnswer($answer);
        return count($answer->Response);
    }
}