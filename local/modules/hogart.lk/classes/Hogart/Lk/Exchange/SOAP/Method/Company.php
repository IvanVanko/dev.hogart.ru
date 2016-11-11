<?php
/**
 * By: Ivan Kiselev aka shaqito@gmail.com
 * Using: PhpStorm.
 * Date: 02.08.2016 22:24
 */

namespace Hogart\Lk\Exchange\SOAP\Method;


use Bitrix\Main\Type\Date;
use Hogart\Lk\Entity\CompanyTable;
use Hogart\Lk\Entity\ContactRelationTable;
use Hogart\Lk\Entity\ContactTable;
use Hogart\Lk\Entity\KindOfActivityTable;
use Hogart\Lk\Exchange\SOAP\AbstractMethod;
use Bitrix\Main\Entity\UpdateResult;
use Hogart\Lk\Exchange\SOAP\AbstractPutRequest;
use Hogart\Lk\Exchange\SOAP\MethodException;
use Hogart\Lk\Exchange\SOAP\Request;
use Hogart\Lk\Exchange\SOAP\Response;
use Hogart\Lk\Exchange\SOAP\ResponseObject;

/**
 * Class Company - добавление Компании и Видов деятельности
 * @package Hogart\Lk\Exchange\SOAP\Method
 */
class Company extends AbstractMethod
{
    /**
     * {@inheritDoc}
     */
    function getName()
    {
        return "Company";
    }

    public function companyPut(AbstractPutRequest $request)
    {
        $response = $this->client->getSoapClient()->CompanyPut($request->__toRequest());
        foreach ($response->return->Response as $company) {
            CompanyTable::update($company->ID_Site, [
                'guid_id' => $company->ID
            ]);
        }
        return $response;
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
        global $DB;
        $answer = new Response();
        $response = $this->getCompanies();
        $activities = [];

        foreach ($response->return->KindsOfActivity as $kind_of_activity) {
            $data = [
                'guid_id' => $kind_of_activity->ID,
                'name' => $kind_of_activity->Name,
                'description' => (string)$kind_of_activity->Description,
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
            if ($company->Comp_ID_Chief == '00000000-0000-0000-0000-000000000000' || null === $company->Comp_ID_Chief)
                $chief['id'] = 0;
            else {
                $chief = ContactTable::getByField('guid_id', $company->Comp_ID_Chief);
                if (empty($chief['id'])) {
                    $answer->addResponse(new ResponseObject($company->Comp_ID, new MethodException(MethodException::ERROR_NO_CHIEF, [$company->Comp_ID_Chief])));
                    continue;
                }
            }

            $DB->StartTransaction();
            $result = CompanyTable::createOrUpdateByField([
                'guid_id' => $company->Comp_ID,
                'kind_activity_id' => $activities[$company->Comp_ID_KindOfActivity] ? : '',
                'name' => (string)$company->Comp_Name,
                'type' => intval($company->Comp_OOOIPFL),
                'type_form' => (string)$company->Comp_OOO_type,
                'inn' => (string)$company->Comp_INN,
                'kpp' => (string)$company->Comp_KPP,
                'date_fact_address' => new Date((string)$company->Comp_DataFactAdress, 'Y-m-d'),
                'chief_contact_id' => $chief['id'] ? : 0,
                'certificate_number' => (string)$company->Comp_Certificate_Number,
                'certificate_date' => new Date($company->Comp_Certificate_Date, 'Y-m-d'),
                'doc_pass' => intval($company->Comp_DocPass),
                'doc_serial' => (string)$company->Comp_DocSerial,
                'doc_number' => (string)$company->Comp_DocNumber,
                'doc_ufms' => (string)$company->Comp_DocUFMS,
                'doc_date' => new Date($company->Comp_DocDate, 'Y-m-d'),
                'is_active' => !$company->deletion_mark
            ], 'guid_id');

            if ($result->getErrorCollection()->count()) {
                $error = $result->getErrorCollection()->current();
                $answer->addResponse(new ResponseObject($company->Comp_ID, new MethodException(MethodException::ERROR_BITRIX, [$error->getMessage(), $error->getCode()])));
                $DB->Rollback();
                continue;
            } else {
                if ($result->getId()) {
                    if ($result instanceof UpdateResult) {
                        $this->client->getLogger()->notice("Обновлена запись Компании {$result->getId()} ({$company->Comp_ID})");
                    } else {
                        $this->client->getLogger()->notice("Добавлена запись Компании {$result->getId()} ({$company->Comp_ID})");
                    }
                    $answer->addResponse(new ResponseObject($company->Comp_ID));
                } else {
                    $answer->addResponse(new ResponseObject($company->Comp_ID, new MethodException(MethodException::ERROR_UNDEFINED)));
                    $DB->Rollback();
                    continue;
                }
            }

            $company_id = $result->getId();
            foreach ($company->Company_Contacts as $company_contact) {
                if (empty($company_contact->Cont_ID)) continue;

                $contact = ContactTable::getByField("guid_id", $company_contact->Cont_ID);
                if (empty($contact['id'])) continue;

                $resultRelation = ContactRelationTable::replace([
                    'contact_id' => intval($contact['id']),
                    'owner_id' => $company_id,
                    'owner_type' => ContactRelationTable::OWNER_TYPE_CLIENT_COMPANY,
                    'post' => (string)$company_contact->Cont_Post
                ]);

                if (!empty($resultRelation->getId())) {
                    $this->client->getLogger()->notice("Обновлена связь Контакта ({$company_contact->Cont_ID}) и Компании клиента ({$company->Comp_ID})");
                }
            }
            $DB->Commit();
        }
        $this->companyAnswer($answer);
        return count($answer->Response);
    }
}