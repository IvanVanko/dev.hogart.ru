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
use Hogart\Lk\Exchange\SOAP\Method\Address\ResponseObject;

class Address extends AbstractMethod
{
    /**
     * @inheritDoc
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
            $types[$address_type->ID] = $res->getId();
        }
        foreach ($result->return->Address as $address) {
            /** @var AbstractEntity $relTable */
            $relTable = AddressTable::$types[$address->Adr_Owner_Type]['table'];

            $owner = $relTable::getList([
                'filter' => [
                    "=" . AddressTable::$types[$address->Adr_Owner_Type]['rel'] => $address->Adr_ID_Owner
                ]
            ])->fetch();

            $owner_id = $owner[AddressTable::$types[$address->Adr_Owner_Type]['rel_id']];
            $data = [
                'owner_id' => $owner_id,
                'owner_type' => $address->Adr_Owner_Type,
                'type_id' => $types[$address->Adr_ID_Address_Type],
                'postal_code' => $address->Adr_ID_Index,
                'region' => $address->Adr_Region,
                'city' => $address->Adr_City,
                'street' => $address->Adr_Street,
                'house' => $address->Adr_House,
                'building' => $address->Adr_Building,
                'flat' => $address->Adr_Flat,
                'kladr_code' => $address->Adr_Kod_KLADR,
                'is_active' => !$address->deletion_mark
            ];
            $result = AddressTable::replace($data);
            $responseObject = new ResponseObject($address->Adr_ID_Owner, $address->Adr_Owner_Type, $address->Adr_ID_Address_Type);
            if ($result->getErrorCollection()->count()) {
                $error = $result->getErrorCollection()->current();
                $this->client->getLogger()->error($error->getMessage() . " (" . $error->getCode() . ")");
                $responseObject->setError(new MethodException($error->getMessage(), $error->getCode()));
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