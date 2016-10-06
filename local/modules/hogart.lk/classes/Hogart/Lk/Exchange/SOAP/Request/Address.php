<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 06/10/2016
 * Time: 21:57
 */

namespace Hogart\Lk\Exchange\SOAP\Request;


use Hogart\Lk\Entity\AddressTypeTable;
use Hogart\Lk\Exchange\SOAP\AbstractPutRequest;

class Address extends AbstractPutRequest
{
    /** @var array  */
    protected $addresses = [];
    /** @var array  */
    protected $types = [];

    /**
     * Address constructor.
     * @param array $addresses
     */
    public function __construct(array $addresses)
    {
        foreach ($addresses as $address) {
            $type = AddressTypeTable::getRowById($address['type_id']);
            $this->types[] = (object)[
                "ID" => $type['guid_id'],
                "Name" => $type['name']
            ];
            $this->addresses[] = (object)[
                "Adr_ID_Owner" => $address['owner_id'],
                "Adr_Owner_Type" => $address['owner_type'],
                "Adr_ID_Address_Type" => $type['guid_id'],
                "Adr_ID_Site" => $address['guid_id'],
                "Adr_Kod_KLADR" => $address['kladr_code'],
                "Adr_ID_Index" => $address['postal_code'],
                "Adr_Region" => $address['region'],
                "Adr_City" => $address['city'],
                "Adr_Street" => $address['street'],
                "Adr_House" => $address['house'],
                "Adr_Building" => $address['building'],
                "Adr_Flat" => $address['flat'],
                "deletion_mark" => !$address['is_active'],
            ];

        }
    }

    protected function request()
    {
        return ['Address' => $this->addresses, 'Address_Types' => $this->types];
    }
}
