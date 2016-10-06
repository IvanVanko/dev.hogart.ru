<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 02/08/16
 * Time: 23:33
 */

namespace Hogart\Lk\Exchange\SOAP\Method;


use Hogart\Lk\Entity\AbstractEntity;
use Hogart\Lk\Entity\ContactInfoTable;
use Hogart\Lk\Exchange\SOAP\AbstractMethod;
use Bitrix\Main\Entity\UpdateResult;
use Hogart\Lk\Exchange\SOAP\MethodException;
use Hogart\Lk\Exchange\SOAP\Request;
use Hogart\Lk\Exchange\SOAP\Response;
use Hogart\Lk\Exchange\SOAP\ResponseObject;

class ContactInfo extends AbstractMethod
{
    /**
     * {@inheritDoc}
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
        foreach ($response->return->Info as $info) {

            /** @var AbstractEntity $relTable */
            $relTable = ContactInfoTable::$types[$info->Info_Owner_Type]['table'];
            $owner = $relTable::getByField(ContactInfoTable::$types[$info->Info_Owner_Type]['rel'], $info->Info_ID_Owner);
            $owner_id = $owner[ContactInfoTable::$types[$info->Info_Owner_Type]['rel_id']];
            if(empty($owner_id)){
                $answer->addResponse(
                    new ResponseObject($info->Info_ID, new MethodException(ContactInfoTable::$types[$info->Info_Owner_Type]["error"], [$info->Info_ID_Owner]))
                );
                continue;
            }

            $values = explode(',', $info->Info_Value);
            foreach ($values as $value) {
                $result = ContactInfoTable::createOrUpdateByField([
                    'd_guid_id' => $info->Info_ID,
                    'owner_id' => $owner_id,
                    'owner_type' => intval($info->Info_Owner_Type),
                    'info_type' => intval($info->Info_Type),
                    'phone_kind' => intval($info->Info_PhoneKind),
                    'value' => (string)trim($value),
                    'is_active' => !$info->deletion_mark,
                ], "d_guid_id");
                if ($result->getErrorCollection()->count()) {
                    $error = $result->getErrorCollection()->current();
                    $answer->addResponse(new ResponseObject($info->Info_ID, new MethodException(MethodException::ERROR_BITRIX, [$error->getMessage(), $error->getCode()])));
                    continue 2;
                } else {
                    if ($result->getId()) {
                        if ($result instanceof UpdateResult) {
                            $this->client->getLogger()->notice("Обновлена запись Контакта {$result->getId()} ({$info->Info_ID})");
                        } else {
                            $this->client->getLogger()->notice("Добавлена запись Контакта {$result->getId()} ({$info->Info_ID})");
                        }
                        $answer->addResponse(new ResponseObject($info->Info_ID));
                    } else {
                        $answer->addResponse(new ResponseObject($info->Info_ID, new MethodException(MethodException::ERROR_UNDEFINED)));
                        continue 2;
                    }
                }
            }
        }
        
        $this->contactInfoAnswer($answer);
        return count($answer->Response);
    }
}