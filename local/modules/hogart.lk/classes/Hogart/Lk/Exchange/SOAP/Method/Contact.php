<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 02/08/16
 * Time: 23:19
 */

namespace Hogart\Lk\Exchange\SOAP\Method;


use Bitrix\Main\Entity\UpdateResult;
use Hogart\Lk\Entity\CompanyTable;
use Hogart\Lk\Entity\ContactRelationTable;
use Hogart\Lk\Entity\ContactTable;
use Hogart\Lk\Entity\HogartCompanyTable;
use Hogart\Lk\Exchange\SOAP\AbstractMethod;
use Hogart\Lk\Exchange\SOAP\MethodException;
use Hogart\Lk\Exchange\SOAP\Request;
use Hogart\Lk\Exchange\SOAP\Response;
use Hogart\Lk\Exchange\SOAP\ResponseObject;

class Contact extends AbstractMethod
{
    /**
     * @inheritDoc
     */
    function getName()
    {
        return "Contact";
    }

    public function getContacts()
    {
        return $this->client->getSoapClient()->ContactGet(new Request());
    }

    public function contactAnswer(Response $response)
    {
        if (count($response->Response) && $this->is_answer) {
            return $this->client->getSoapClient()->ContactAnswer($response);
        }
    }

    public function updateContacts()
    {
        $answer = new Response();
        $response = $this->getContacts();
        foreach ($response->return->Contact as $contact) {
            $result = ContactTable::createOrUpdateByField([
                'guid_id' => $contact->Cont_ID,
                'name' => $contact->Cont_Name,
                'last_name' => $contact->Cont_Surname,
                'middle_name' => $contact->Cont_Middle_Name,
                'is_active' => !$contact->deletion_mark,
            ], "guid_id");

            if ($result->getErrorCollection()->count()) {
                $error = $result->getErrorCollection()->current();
                $answer->addResponse(new ResponseObject($contact->Cont_ID, new MethodException(MethodException::ERROR_BITRIX, [$error->getMessage(), $error->getCode()], $error)));
            } else {
                if ($result->getId()) {
                    if ($result instanceof UpdateResult) {
                        $this->client->getLogger()->notice("Обновлена запись Контакта {$result->getId()} ({$contact->Cont_ID})");
                    } else {
                        $this->client->getLogger()->notice("Добавлена запись Контакта {$result->getId()} ({$contact->Cont_ID})");
                    }
                    $owner_type = ContactRelationTable::OWNER_TYPE_CLIENT_COMPANY;
                    $company = CompanyTable::getByField('guid_id', $contact->Cont_ID_Company);
                    if(!$company) {
                        $owner_type = ContactRelationTable::OWNER_TYPE_HOGART_COMPANY;
                        $company = HogartCompanyTable::getByField('guid_id', $contact->Cont_ID_Company);
                    }

                    if (!empty($company['id'])) {
                        $resultRelation = ContactRelationTable::replace([
                            'contact_id' => $result->getId(),
                            'owner_id' => $company['id'],
                            'owner_type' => $owner_type
                        ]);
                        if (!empty($resultRelation->getId())) {
                            $this->client->getLogger()->notice("Обновлена связь Контакта ({$result->getId()}) и Компании клиента ({$company['id']})");
                        }
                    }
                    $answer->addResponse(new ResponseObject($contact->Cont_ID));
                } else {
                    $answer->addResponse(new ResponseObject($contact->Cont_ID, new MethodException(MethodException::ERROR_UNDEFINED)));
                }
            }
        }
        $this->contactAnswer($answer);
        return count($answer->Response);
    }
}