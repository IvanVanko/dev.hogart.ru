<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 02/08/16
 * Time: 11:59
 */

namespace Hogart\Lk\Exchange\SOAP\Method;


use Bitrix\Main\UserTable;
use Hogart\Lk\Entity\UserStoreTable;
use Hogart\Lk\Exchange\SOAP\AbstractMethod;

class Account extends AbstractMethod
{
    /**
     * @inheritDoc
     */
    function getName()
    {
        return "Account";
    }

    /**
     * @return mixed
     */
    public function getAccounts()
    {
        return $this->client->getSoapClient()->AccountGet(new Request());
    }

    /**
     * @param Response $response
     * @return mixed
     */
    public function accountAnswer(Response $response)
    {
        $response = $this->client->getSoapClient()->AccountAnswer($response);
        $this->client->getLogger()->debug("Ответ на метод AccountAnswer: " . ($response->return ? "true" : "false"));
        return $response;
    }

    public function createOrUpdateAccounts()
    {
        $answer = new Response();
        $response = $this->getAccounts();

        foreach ($response->return->AccInfo as $accountInfo) {
            $user = UserTable::getList([
                'filter' => [
                    '=XML_ID' => $accountInfo->Acc_ID
                ]
            ])->fetch();

            if (!empty($user)) {
                foreach ($accountInfo->Acc_Warehouses as $acc_Warehouse) {
                    UserStoreTable::replace([
                        'user_id' => $user["ID"],
                        'store_guid' => $acc_Warehouse,
                        'is_main' => $accountInfo->Acc_ID_Main_Warehouse == $acc_Warehouse
                    ]);
                    $this->client->getLogger()->notice("Обработан склад клиента {$acc_Warehouse}: добавлена запись");
                }
            } else {

            }

            $answer->addResponse(new ResponseObject($accountInfo->Acc_ID));
        }


        return count($answer->Response);
    }
}
