<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 02/08/16
 * Time: 23:33
 */

namespace Hogart\Lk\Exchange\SOAP\Method;


use Hogart\Lk\Entity\CompanyTable;
use Hogart\Lk\Entity\ContactInfoTable;
use Hogart\Lk\Exchange\SOAP\AbstractMethod;
use Bitrix\Main\Entity\UpdateResult;

class ContactInfo extends AbstractMethod
{
    /**
     * @inheritDoc
     */
    function getName()
    {
        return "ContactInfo";
    }

    public function getContactsInfo()
    {
        return $this->client->getSoapClient()->ContactInfoGet(new Request());
    }

    public function contactInfoAnswer(Response $response)
    {
        if (count($response->Response) && $this->is_answer) {
            return $this->client->getSoapClient()->ContactInfoAnswer($response);
        }
    }


    public function updateContactsInfo()
    {
        $answer = new Response();
        $response = $this->getContactsInfo();
        foreach ($response->Info as $info) {
            $company = CompanyTable::getByField('d_guid_id', $info->Info_ID);
            $result = ContactInfoTable::createOrUpdateByField([
                'd_guid_id' => $info->Info_ID,
                'company_id' => $company['id'],
                'type' => $info->Info_Type,
                'phone_kind' => $info->Info_PhoneKind,
                'value' => $info->Info_Value,
                'is_active' => !$info->deletion_mark,
            ], "d_guid_id");

            if ($result->getErrorCollection()->count()) {
                $error = $result->getErrorCollection()->current();
                $answer->addResponse(new ResponseObject($info->Info_ID, new MethodException($error->getMessage(), $error->getCode())));
            } else {
                if ($result->getId()) {
                    if ($result instanceof UpdateResult) {
                        $this->client->getLogger()->notice("Обновлена запись Контакта {$result->getId()} ({$info->Info_ID})");
                    } else {
                        $this->client->getLogger()->notice("Добавлена запись Контакта {$result->getId()} ({$info->Info_ID})");
                    }
                    $answer->addResponse(new ResponseObject($info->Info_ID));
                } else {
                    $answer->addResponse(new ResponseObject($info->Info_ID, new MethodException(self::$default_errors[self::ERROR_UNDEFINED], self::ERROR_UNDEFINED)));
                }
            }


        }
    }
}