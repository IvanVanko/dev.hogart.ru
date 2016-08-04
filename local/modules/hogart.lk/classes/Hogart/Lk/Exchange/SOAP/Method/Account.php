<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 02/08/16
 * Time: 11:59
 */

namespace Hogart\Lk\Exchange\SOAP\Method;


use Bitrix\Catalog\StoreTable;
use Bitrix\Main\UserTable;
use Hogart\Lk\Entity\AccountCompanyRelationTable;
use Hogart\Lk\Entity\AccountTable;
use Hogart\Lk\Entity\AccountStoreRelationTable;
use Hogart\Lk\Entity\ContactTable;
use Hogart\Lk\Entity\StaffRelationTable;
use Hogart\Lk\Entity\StaffTable;
use Hogart\Lk\Exchange\RabbitMQ\Consumer;
use Hogart\Lk\Exchange\RabbitMQ\Exchange\AccountExchange;
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

    /**
     * @todo Доработка метода
     * 
     * @return int
     * @throws \Bitrix\Main\ArgumentException
     */
    public function updateAccounts()
    {
        $answer = new Response();
        $response = $this->getAccounts();

        foreach ($response->return->AccInfo as $accountInfo) {
            $answer->addResponse($response_object = new ResponseObject($accountInfo->Acc_ID));

            $account = AccountTable::getByField('user_guid_id', $accountInfo->Acc_ID);

            if (empty($account['id'])) {
                $user = UserTable::getList(['filter' => ['=EMAIL' => $accountInfo->Acc_Login]])->fetch();
                if (empty($user['ID'])) {
                    // добавляем пользователя в Bitrix
                    $password = randString(12);
                    $user_obj = new\CUser;
                    $user['ID'] = $user_obj->Add(
                        [
                            'EMAIL' => $accountInfo->Acc_Login,
                            'LOGIN' => $accountInfo->Acc_Login,
                            'ACTIVE' => (!$accountInfo->deletion_mark ? 'Y' : 'N'),
                            'PASSWORD' => $password,
                            'CONFIRM_PASSWORD' => $password,
                        ]
                    );

                    if (empty($user['ID'])) {
                        $response_object->setError(new MethodException("Ошибка создания пользователя '{$accountInfo->Acc_Login}': {$user_obj->LAST_ERROR} "));
                        continue;
                    }
                    $accountExchange = (new AccountExchange())->useConsumer(Consumer::getInstance());
                    $accountExchange->getExchange()->publish($accountInfo->Acc_Login, $accountExchange->getPublishKey('send_password'), AMQP_NOPARAM, ['delivery_mode' => 2]);
                }
            } else {
                $user['ID'] = $account['user_id'];
            }

            $contact = ContactTable::getByField('guid_id', $accountInfo->Acc_ID_Contact);
            $main_manager = StaffTable::getByField('guid_id', $accountInfo->Acc_ID_Main_Manager);
            $head_account = AccountTable::getByField('user_guid_id', $accountInfo->Acc_ID_Head);
            $main_contract = ContactTable::getByField('guid_id', $accountInfo->Acc_ID_Main_Contract);
            $main_store = StoreTable::getList(['filter'=>['=XML_ID' => $accountInfo->Acc_ID_Main_Warehouse]])->fetch();

            $result = AccountTable::createOrUpdateByField([
                'user_guid_id' => $accountInfo->Acc_ID,
                'user_id' => $user['ID'],
                'contact_id' => $contact['id'] ?: 0,
                'main_manager_id' => $main_manager['id'] ?: 0,
                'main_store_id' => $main_store['ID'] ?: 0,
                'head_account_id' => $head_account['id'] ?: 0,
                'main_contract_id' => $main_contract['id'] ?: 0,
                'is_promo_accesss' => $accountInfo->Acc_PromoAccess,
                'is_active' => !$accountInfo->deletion_mark
            ], 'user_guid_id');

            if (!empty(!$result->getId())) {
                foreach ($accountInfo->Acc_Warehouses as $acc_Warehouse) {
                    AccountStoreRelationTable::replace([
                        'account_id' => $result->getId(),
                        'store_guid' => $acc_Warehouse,
                    ]);
                    $this->client->getLogger()->notice("Обработан склад клиента {$acc_Warehouse}: добавлена запись");
                }

                foreach ($accountInfo->Acc_Managers as $acc_Manager) {
                    $manager = StaffTable::getByField('guid_id', $acc_Manager);
                    StaffRelationTable::replace([
                        'staff_id' => $manager['id'],
                        'owner_id' => $result->getId(),
                        'owner_type' => StaffRelationTable::OWNER_TYPE_ACCOUNT,
                        'is_main' => ($acc_Manager == $accountInfo->Acc_ID_Main_Manager),
                    ]);
                    $this->client->getLogger()->notice("Обработан менеджер клиента {$acc_Manager}: добавлена запись");
                }

                foreach ($accountInfo->Acc_Companies as $acc_Company) {
                    $company = StaffTable::getByField('guid_id', $acc_Company);
                    AccountCompanyRelationTable::replace([
                        'account_id' => $result->getId(),
                        'company_id' => $company['id'],
                    ]);
                    $this->client->getLogger()->notice("Обработана компания клиента {$acc_Company}: добавлена запись");
                }
            }
        }

        return count($answer->Response);
    }
}
