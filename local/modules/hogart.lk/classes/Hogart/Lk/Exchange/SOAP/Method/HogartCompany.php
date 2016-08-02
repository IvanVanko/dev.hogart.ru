<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 02/08/16
 * Time: 22:54
 */

namespace Hogart\Lk\Exchange\SOAP\Method;


use Bitrix\Main\Entity\UpdateResult;
use Hogart\Lk\Entity\HogartCompanyTable;
use Hogart\Lk\Entity\StaffTable;
use Hogart\Lk\Exchange\SOAP\AbstractMethod;

class HogartCompany extends AbstractMethod
{
    /**
     * @inheritDoc
     */
    function getName()
    {
        return "HogartCompany";
    }

    public function getOrganisations()
    {
        return $this->client->getSoapClient()->OrganisationGet(new Request());
    }

    public function createOrUpdateOrganisations()
    {
        $response = $this->getOrganisations();
        foreach ($response->return->Hogart as $organisation) {
            $staff = StaffTable::getList([
                'filter' => [
                    '=guid_id' => $organisation->Hogart_ID_Chief
                ]
            ])->fetch();
            $result = HogartCompanyTable::createOrUpdateByField([
                "guid_id" => $organisation->Hogart_ID,
                "name" => $organisation->Hogart_Name,
                "inn" => $organisation->Hogart_INN,
                "kpp" => $organisation->Hogart_KPP,
                "chief_id" => $staff["id"]
            ], "guid_id");
            if ($response instanceof UpdateResult) {
                $this->client->getLogger()->notice("Обновлена запись Организации Хогарт {$result->getId()}");
            } else {
                $this->client->getLogger()->notice("Добавлена запись Организации Хогарт {$result->getId()} ({$organisation->Hogart_ID})");
            }
        }
    }
}