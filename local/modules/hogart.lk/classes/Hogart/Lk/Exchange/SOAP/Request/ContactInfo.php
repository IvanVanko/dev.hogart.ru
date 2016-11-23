<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 21/11/2016
 * Time: 12:43
 */

namespace Hogart\Lk\Exchange\SOAP\Request;


use Hogart\Lk\Entity\ContactInfoTable;
use Hogart\Lk\Exchange\SOAP\AbstractPutRequest;
use Hogart\Lk\Exchange\SOAP\LazyRequest;

class ContactInfo extends AbstractPutRequest
{
    /** @var array  */
    protected $info = [];

    /**
     * @inheritDoc
     */
    public function __construct($info = [])
    {
        foreach ($info as $item) {
            $this->info[] = (object)[
                "Info_ID_Owner" => new LazyRequest(function ($info) {
                    return (string)ContactInfoTable::getOwnerRel($info);
                }, [$item]),
                "Info_Owner_Type" => intval($item['owner_type']),
                "Info_ID" => $item['d_guid_id'],
                "Info_ID_Site" => $item['guid_id'],
                "Info_Type" => intval($item['info_type']),
                "Info_PhoneKind" => intval($item['phone_kind']),
                "Info_Value" => (string)(intval($item['info_type']) == ContactInfoTable::TYPE_PHONE ? ContactInfoTable::formatPhone($item['info_type']) : $item['value']),
                "deletion_mark" => !$item['is_active'],
            ];
        }
    }

    protected function request()
    {
        return ['Info' => $this->info];
    }
}