<?php
/**
 * By: Ivan Kiselev aka shaqito@gmail.com
 * Using: PhpStorm.
 * Date: 02.08.2016 22:24
 */

namespace Hogart\Lk\Exchange\SOAP\Method;


use Bitrix\Main\Type\Date;
use Hogart\Lk\Entity\AccountTable;
use Hogart\Lk\Entity\CompanyTable;
use Hogart\Lk\Entity\ContactTable;
use Hogart\Lk\Entity\KindOfActivityTable;
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
        return $this->client->getSoapClient()->CompanyGet(new Request());
    }

    public function companyAnswer(Response $response)
    {
        if (count($response->Response) && $this->is_answer) {
            return $this->client->getSoapClient()->CompanyAnswer($response);
        }
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
            $result = KindOfActivityTable::createOrUpdateByField($data, 'guid_id');
            $activities[$kind_of_activity->ID] = $result->getId();

            if (!empty($result->getId())) {
                if ($result instanceof UpdateResult) {
                    $this->client->getLogger()->notice("Обновлена запись 'Типа занятости компании' {$result->getId()} ({$kind_of_activity->ID})");
                } else {
                    $this->client->getLogger()->notice("Добавлена запись 'Типа занятости компании' {$result->getId()} ({$kind_of_activity->ID})");
                }
            }
        }
        foreach ($response->return->Company as $company) {
            $chief = ContactTable::getByField('guid_id', $company->Comp_ID_Chief);

            if(empty($chief['id'])){
                $answer->addResponse(new ResponseObject($company->Comp_ID, new MethodException('Задан несуществующий Comp_ID_Chief')));
                continue;
            }

            $result = CompanyTable::createOrUpdateByField([
                'guid_id' => $company->Comp_ID,
                'kind_activity_id' => $activities[$company->Comp_ID_KindOfActivity] ? : '',
                'name' => $company->Comp_Name,
                'type' => $company->Comp_OOOIPFL,
                'type_form' => (string)$company->Comp_OOO_type,
                'inn' => $company->Comp_INN,
                'kpp' => $company->Comp_KPP,
                'date_fact_address' => new Date((string)$company->Comp_DataFactAdress, 'Y-m-d'),
                'chief_contact_id' => $chief['id'] ? : 0,
                'certificate_number' => $company->Comp_Certificate_Number,
                'certificate_date' => new Date($company->Comp_Certificate_Date, 'Y-m-d'),
                'doc_pass' => intval($company->Comp_DocPass),
                'doc_serial' => $company->Comp_DocSerial,
                'doc_number' => $company->Comp_DocNumber,
                'doc_ufms' => $company->Comp_DocUFMS,
                'doc_date' => new Date($company->Comp_DocDate, 'Y-m-d'),
                'is_active' => !$company->deletion_mark
            ], 'guid_id');

            if ($result->getErrorCollection()->count()) {
                $error = $result->getErrorCollection()->current();
                $answer->addResponse(new ResponseObject($company->Comp_ID, new MethodException($error->getMessage(), intval($error->getCode()))));
            } else {
                if ($result->getId()) {
                    if ($result instanceof UpdateResult) {
                        $this->client->getLogger()->notice("Обновлена запись Компании {$result->getId()} ({$company->Comp_ID})");
                    } else {
                        $this->client->getLogger()->notice("Добавлена запись Компании {$result->getId()} ({$company->Comp_ID})");
                    }
                    $answer->addResponse(new ResponseObject($company->Comp_ID));
                } else {
                    $answer->addResponse(new ResponseObject($company->Comp_ID, new MethodException(self::$default_errors[self::ERROR_UNDEFINED], self::ERROR_UNDEFINED)));
                }
            }
        }
        $this->companyAnswer($answer);
        return count($answer->Response);
    }
}