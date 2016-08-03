<?php
/**
 * By: Ivan Kiselev aka shaqito[at]gmail.com
 * Via: PhpStorm.
 * At: 03.08.2016 11:55
 */

namespace Hogart\Lk\Exchange\SOAP\Method;


use Hogart\Lk\Entity\ContractTable;
use Hogart\Lk\Entity\CompanyTable;
use Hogart\Lk\Entity\HogartCompanyTable;
use Hogart\Lk\Exchange\SOAP\AbstractMethod;
use Bitrix\Main\UserTable;
use Bitrix\Currency\CurrencyTable;
use Bitrix\Main\Entity\UpdateResult;

/**
 * Class Contract - добавление Договоров (они же Контракты)
 * @package Hogart\Lk\Exchange\SOAP\Method
 */
class Contract extends AbstractMethod
{
    /**
     * @inheritDoc
     */
    function getName()
    {
        return "Contract";
    }

    public function getContracts()
    {
        return $this->client->getSoapClient()->ContractsGet(new Request());
    }

    public function updateContracts()
    {
        $answer = new Response();
        $response = $this->getContrcats();
        $activities = [];
        
        foreach ($response->return->Contract as $contract) {
            $currency = CurrencyTable::getList([
                'filter' => [
                    '=CURRENCY' => $contract->Contr_ID_Money
                ]
            ]);
            $clientCompany = CompanyTable::getList([
                'filter' => [
                    '=guid_id' => $contract->Contr_ID_Company
                ]
            ])->fetch();

            $hogartCompany = HogartCompanyTable::getList([
                'filter' => [
                    '=guid_id' => $contract->Contr_ID_Hogart
                ]
            ])->fetch();


            $result = ContractTable::createOrUpdateByField([
                'company_id' => $clientCompany['ID'],
                'hogart_company_id' => $hogartCompany['ID'],
                'guid_id' => $contract->Contr_ID,
                'number' => $contract->Contr_Number,
                'start_date' => $contract->Contr_Date,
                'end_date' => $contract->Contr_DateTO,
                'extension' => $contract->Contr_Prolon,
                'currency_code' => $currency['ID'],
                'perm_item' => ($contract->Contr_Perm_Item === 'true'),
                'prem_promo' => ($contract->Contr_Perm_Promo === 'true'),
                'perm_clearing' => ($contract->Contr_Perm_Clearing === 'true'),
                'perm_card' => ($contract->Contr_Perm_Card === 'true'),
                'perm_cash' => ($contract->Contr_Perm_Cash === 'true'),
                'cash_control' => ($contract->Contr_Cash_Control === 'true'),
                'cash_limit' => $contract->Contr_Limit_Cash,
                'deferral' => $contract->Contr_Defferal,
                'credit_limit' => $contract->Contr_Credit_Limit,
                'have_original' => ($contract->Contr_IHaveDocs === 'true'),
                'accept' => ($contract->Contr_Accept === 'true'),
                'vat_rate' => $contract->Contr_Accept,
                'vat_include' => ($contract->Contr_VAT_Include === 'true'),
                'is_active' => !($contract->deletion_mark === 'true'),
            ], 'guid_id');

            if (!empty($result->getId())) {
                if ($result instanceof UpdateResult) {
                    $this->client->getLogger()->notice("Обновлена запись Договора {$result->getId()}");
                } else {
                    $this->client->getLogger()->notice("Добавлена запись Договора {$result->getId()} ({$contract->Comp_ID})");
                }
            }

            $answer->addResponse(new ResponseObject($contract->Contr_ID));
        }

        return count($answer->Response);
    }
}