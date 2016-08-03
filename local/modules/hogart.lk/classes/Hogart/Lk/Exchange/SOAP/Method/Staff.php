<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 02/08/16
 * Time: 23:08
 */

namespace Hogart\Lk\Exchange\SOAP\Method;


use Bitrix\Main\Entity\UpdateResult;
use Hogart\Lk\Entity\StaffTable;
use Hogart\Lk\Exchange\SOAP\AbstractMethod;

class Staff extends AbstractMethod
{
    /**
     * @inheritDoc
     */
    function getName()
    {
        return "Staff";
    }

    /**
     * @return mixed
     */
    public function getStaff()
    {
        return $this->client->getSoapClient()->StaffGet(new Request());
    }

    /**
     * @param Response $response
     */
    public function staffAnswer(Response $response)
    {
        if (count($response->Response) && $this->is_answer) {
            return $this->client->getSoapClient()->StaffAnswer($response);
        }
    }

    public function createOrUpdateStaff()
    {
        $answer = new Response();
        $response = $this->getStaff();
        foreach ($response->return->Staff_Line as $staff) {
            $chief = StaffTable::getList([
                'filter' => [
                    '=guid_id' => $staff->Staff_ID_Сhief
                ]
            ])->fetch();
            $result = StaffTable::createOrUpdateByField([
                'guid_id' => $staff->Staff_ID,
                'name' => $staff->Staff_Name,
                'last_name' => $staff->Staff_Surname,
                'middle_name' => $staff->Staff_Middle_Name,
                'chief_id' => $chief['id'] ? : 0,
                'photo_guid' => $staff->Staff_Foto,
                'branch' => $staff->Staff_Branch
            ], "guid_id");
            
            if ($result->getErrorCollection()->count()) {
                $error = $result->getErrorCollection()->current();
                $answer->addResponse(new ResponseObject($staff->Staff_ID, new MethodException($error->getMessage(), $error->getCode())));
                $this->client->getLogger()->error($error->getMessage() . " (" . $error->getCode() . ")");
            } else {
                if ($result->getId()) {
                    if ($result instanceof UpdateResult) {
                        $this->client->getLogger()->notice("Обновлена запись Сотрудника Хогарт {$result->getId()} ({$staff->Staff_ID})");
                    } else {
                        $this->client->getLogger()->notice("Добавлена запись Сотрудника Хогарт {$result->getId()} ({$staff->Staff_ID})");
                    }
                    $answer->addResponse(new ResponseObject($staff->Staff_ID));
                } else {
                    $answer->addResponse(new ResponseObject($staff->Staff_ID, new MethodException(self::$default_errors[self::ERROR_UNDEFINED], self::ERROR_UNDEFINED)));
                    $this->client->getLogger()->error(self::$default_errors[self::ERROR_UNDEFINED] . " (" . self::ERROR_UNDEFINED . ")");
                }
            }
        }
        $this->staffAnswer($answer);
        return count($answer->Response);
    }

}