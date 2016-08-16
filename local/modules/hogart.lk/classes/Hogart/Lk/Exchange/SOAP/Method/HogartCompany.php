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
use Hogart\Lk\Exchange\SOAP\MethodException;
use Hogart\Lk\Exchange\SOAP\Request;
use Hogart\Lk\Exchange\SOAP\Response;
use Hogart\Lk\Exchange\SOAP\ResponseObject;

class HogartCompany extends AbstractMethod
{
    /**
     * {@inheritDoc}
     */
    function getName()
    {
        return "HogartCompany";
    }

    public function getOrganisations()
    {
        return $this->client->getSoapClient()->OrganisationGet(new Request());
    }

    public function organisationAnswer(Response $response)
    {
        if (count($response->Response) && $this->is_answer) {
            return $this->client->getSoapClient()->OrganisationAnswer($response);
        }
    }

    public function updateHogartCompanies()
    {
        $answer = new Response();
        $response = $this->getOrganisations();
        foreach ($response->return->Hogart as $organisation) {
            $staff = StaffTable::getByField('guid_id', $organisation->Hogart_ID_Chief);
            $result = HogartCompanyTable::createOrUpdateByField([
                "guid_id" => $organisation->Hogart_ID,
                "name" => $organisation->Hogart_Name,
                "inn" => $organisation->Hogart_INN,
                "kpp" => $organisation->Hogart_KPP,
                "chief_id" => $staff["id"] ? : 0,
                "is_active" => !$organisation->deletion_mark
            ], "guid_id");

            if ($result->getErrorCollection()->count()) {
                $error = $result->getErrorCollection()->current();
                $answer->addResponse(new ResponseObject($organisation->Hogart_ID, new MethodException(MethodException::ERROR_BITRIX, [$error->getMessage(), $error->getCode()])));
            } else {
                if ($result->getId()) {
                    if ($result instanceof UpdateResult) {
                        $this->client->getLogger()->notice("Обновлена запись Организации Хогарт {$result->getId()} ({$organisation->Hogart_ID})");
                    } else {
                        $this->client->getLogger()->notice("Добавлена запись Организации Хогарт {$result->getId()} ({$organisation->Hogart_ID})");
                    }
                    $answer->addResponse(new ResponseObject($organisation->Hogart_ID));
                } else {
                    $answer->addResponse(new ResponseObject($organisation->Hogart_ID, new MethodException(MethodException::ERROR_UNDEFINED)));
                }
            }
        }
        $this->organisationAnswer($answer);
        return count($answer->Response);
    }
}