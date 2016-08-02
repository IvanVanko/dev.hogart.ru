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

    public function createOrUpdateStaff()
    {
        $response = $this->getStaff();
        /** @var object $staff */
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
                'chief_id' => $chief['id'],
                'photo_guid' => $staff->Staff_Foto,
                'branch' => $staff->Staff_Branch
            ], "guid_id");
            
            if ($response instanceof UpdateResult) {
                $this->client->getLogger()->notice("Обновлена запись Сотрундника Хогарт {$result->getId()}");
            } else {
                $this->client->getLogger()->notice("Добавлена запись Сотрундника Хогарт {$result->getId()} ({$staff->Staff_ID})");
            }
        }
    }

}