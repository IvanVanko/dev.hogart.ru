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
use Hogart\Lk\Exchange\SOAP\MethodException;
use Hogart\Lk\Exchange\SOAP\Request;
use Hogart\Lk\Exchange\SOAP\Response;
use Hogart\Lk\Exchange\SOAP\ResponseObject;
use Intervention\Image\ImageManagerStatic;

class Staff extends AbstractMethod
{
    /**
     * {@inheritDoc}
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

    public function updateStaff()
    {
        $answer = new Response();
        $response = $this->getStaff();
        foreach ($response->return->Staff_Line as $staff) {
            $chief = StaffTable::getByField('guid_id', $staff->Staff_ID_Сhief);
            if (!empty($staff->Staff_Foto)) {
                $data = base64_decode($staff->Staff_Foto);
                $image = ImageManagerStatic::make($data);
                $staff->Staff_Foto = $image->resize(64, 64)->encode('data-url', 100);
            }
            $result = StaffTable::createOrUpdateByField([
                'guid_id' => $staff->Staff_ID,
                'name' => $staff->Staff_Name,
                'last_name' => $staff->Staff_Surname,
                'middle_name' => $staff->Staff_Middle_Name,
                'chief_id' => $chief['id'] ? : 0,
                'photo' => (string)$staff->Staff_Foto,
                'branch' => (int)$staff->Staff_Branch
            ], "guid_id");
            
            if ($result->getErrorCollection()->count()) {
                $error = $result->getErrorCollection()->current();
                $answer->addResponse(new ResponseObject($staff->Staff_ID, new MethodException(MethodException::ERROR_BITRIX, [$error->getMessage(), $error->getCode()])));
            } else {
                if ($result->getId()) {
                    if ($result instanceof UpdateResult) {
                        $this->client->getLogger()->notice("Обновлена запись Сотрудника Хогарт {$result->getId()} ({$staff->Staff_ID})");
                    } else {
                        $this->client->getLogger()->notice("Добавлена запись Сотрудника Хогарт {$result->getId()} ({$staff->Staff_ID})");
                    }
                    $answer->addResponse(new ResponseObject($staff->Staff_ID));
                } else {
                    $answer->addResponse(new ResponseObject($staff->Staff_ID, new MethodException(MethodException::ERROR_UNDEFINED)));
                }
            }
        }
        $this->staffAnswer($answer);
        return count($answer->Response);
    }
}