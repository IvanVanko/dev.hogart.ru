<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 02/08/16
 * Time: 17:12
 */

namespace Hogart\Lk\Exchange\SOAP\Method;


use Hogart\Lk\Entity\AddressTable;
use Hogart\Lk\Entity\AddressTypeTable;
use Hogart\Lk\Exchange\SOAP\AbstractMethod;

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
        return $this->client->getSoapClient()->AdressesGet(new Request());
    }

    public function updateAddresses()
    {
        $result = $this->getAddresses();
        $types = [];
        foreach ($result->return->Adress_Types as $address_type) {
            $data = [
                'guid_id' => $address_type->ID,
                'name' => $address_type->Name
            ];
            $res = AddressTypeTable::createOrUpdateByField($data, 'guid_id');
            $types[$address_type->ID] = $res->getId();
        }
        foreach ($result->return->Adress as $address) {
            $data = [
                'type_id' => $types[$address->Adr_ID_Adress_Type],
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
            AddressTable::add($data);
        }
    }
}