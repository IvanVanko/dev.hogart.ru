<?php
/**
 * By: Ivan Kiselev aka shaqito@gmail.com
 * Using: PhpStorm.
 * Date: 02.08.2016 22:24
 */

namespace Hogart\Lk\Exchange\SOAP\Method;


use Hogart\Lk\Entity\CompanyTable;
use Hogart\Lk\Exchange\SOAP\AbstractMethod;
use Bitrix\Main\UserTable;
use Bitrix\Main\Entity\UpdateResult;

/**
 * Class Company - добавление Компании и Видов деятельности
 * @package Hogart\Lk\Exchange\SOAP\Method
 */
class Company extends AbstractMethod
{
    /**
     * @inheritDoc
     */
    function getName()
    {
        return "Company";
    }

    public function getCompanies()
    {
        return $this->client->getSoapClient()->CompaniesGet(new Request());
    }

    public function updateCompanies()
    {
        $answer = new Response();
        $response = $this->getCompanies();
        $activities = [];
        foreach ($response->return->KindsOfActivity as $kind_of_activity) {
            $data = [
                'guid_id' => $kind_of_activity->ID,
                'name' => $kind_of_activity->Name,
                'description' => $kind_of_activity->Description,
            ];
            $result = CompanyTable::createOrUpdateByField($data, 'guid_id');
            $activities[$kind_of_activity->ID] = $result->getId();

            if (!empty($result->getId())) {
                if ($result instanceof UpdateResult) {
                    $this->client->getLogger()->notice("Обновлена запись Компании {$result->getId()}");
                } else {
                    $this->client->getLogger()->notice("Добавлена запись Компании {$result->getId()} ({$kind_of_activity->ID})");
                }
            }
        }
        foreach ($response->return->Company as $company) {
            $chief= UserTable::getList([
                'filter' => [
                    '=XML_ID' => $company->Comp_ID_Chief
                ]
            ])->fetch();

            $result = CompanyTable::createOrUpdateByField([
                'kind_activity_id' => $activities[$company->Comp_ID_KindOfActivity],
                'name'=>$company->Comp_Name,
                'type'=>$company->Comp_OOOIPFL,
                'type_form'=>$company->Comp_OOO_tipe,
                'inn'=>$company->Comp_INN,
                'kpp'=>$company->Comp_KPP,
                'date_fact_address'=>$company->Comp_DataFactAdress,
                'chief_id'=>$company->$chief['ID'],
                'certificate_number'=>$company->Comp_Certificate_Number,
                'certificate_date'=>$company->Comp_Certificate_Date,
                'doc_pass'=>$company->Comp_DocPass,
                'doc_serial'=>$company->Comp_DocSerial,
                'doc_number'=>$company->Comp_DocNumber,
                'doc_ufms'=>$company->Comp_DocUFMS,
                'doc_date'=>$company->Comp_DocDate,
                'is_active'=>!$company->deletion_mark
            ], 'guid_id');

            if (!empty($result->getId())) {
                if ($result instanceof UpdateResult) {
                    $this->client->getLogger()->notice("Обновлена запись Компании {$result->getId()}");
                } else {
                    $this->client->getLogger()->notice("Добавлена запись Компании {$result->getId()} ({$company->Comp_ID})");
                }
            }

            $answer->addResponse(new ResponseObject($company->Comp_ID));
        }

        return count($answer->Response);
    }
}