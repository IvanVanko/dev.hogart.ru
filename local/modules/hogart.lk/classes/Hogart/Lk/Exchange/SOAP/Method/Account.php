<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 02/08/16
 * Time: 11:59
 */

namespace Hogart\Lk\Exchange\SOAP\Method;


use Bitrix\Main\DB\SqlExpression;
use Bitrix\Main\UserTable;
use Hogart\Lk\Entity\UserStoreTable;
use Hogart\Lk\Exchange\SOAP\AbstractMethod;
use Hogart\Lk\Exchange\SOAP\Method\Account\AccountAnswer;
use Hogart\Lk\Exchange\SOAP\Method\Account\AccountGet;

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
        return $this->client->AccountGet(new AccountGet());
    }

    /**
     * @param AccountAnswer $answer
     * @return mixed
     */
    public function accountAnswer(AccountAnswer $answer)
    {
        return $this->client->AccountAnswer($answer);
    }

    public function createOrUpdateAccounts()
    {
        $answer = new AccountAnswer();
        $response = $this->getAccounts();

        foreach ($response->return->AccInfo as $accountInfo) {
            $user = UserTable::getList([
                'filter' => [
                    '=XML_ID' => $accountInfo->Acc_ID
                ]
            ])->fetch();

            if (!empty($user)) {

                $stores = UserStoreTable::getList([
                    'filter' => [
                        '=user.ID' => $user['ID'],
                        '@store.XML_ID' => array_values($accountInfo->Acc_Warehouses)
                    ]
                ])->fetchAll();

                foreach ($accountInfo->Acc_Warehouses as $acc_Warehouse) {
                    $result = UserStoreTable::add([
                        'user_id' => $user["ID"],
                        'store_guid' => $acc_Warehouse
                    ]);
                }
            } else {

            }
        }


        return count($answer->Answer);
    }
}
