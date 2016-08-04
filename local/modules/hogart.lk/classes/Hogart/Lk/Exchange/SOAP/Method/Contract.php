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
use Bitrix\Main\Entity\UpdateResult;
use Bitrix\Main\Type\Date;
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

    public function getContract()
    {
        return $this->client->getSoapClient()->ContractGet(new Request());
    }

    public function contractAnswer(Response $response)
    {
        if (count($response->Response) && $this->is_answer) {
            return $this->client->getSoapClient()->ContractAnswer($response);
        }
    }

    public function updateContracts()
    {
        $answer = new Response();
        $response = $this->getContract();

        foreach ($response->return->Contract as $contract) {

            $clientCompany = CompanyTable::getByField('guid_id', $contract->Contr_ID_Company);

            $hogartCompany = HogartCompanyTable::getByField('guid_id', $contract->Contr_ID_Holding);

            $result = ContractTable::createOrUpdateByField([
                'company_id' => $clientCompany['id'],
                'hogart_company_id' => $hogartCompany['id'],
                'guid_id' => $contract->Contr_ID,
                'number' => $contract->Contr_Number,
                'start_date' => new Date((string)$contract->Contr_Date, 'Y-m-d'),
                'end_date' => new Date((string)$contract->Contr_DateTO, 'Y-m-d'),
                'extension' => $contract->Contr_Prolon,
                'currency_code' => $contract->Contr_ID_Money,
                'perm_item' => (bool)$contract->Contr_Perm_Item,
                'prem_promo' => (bool)$contract->Contr_Perm_Promo,
                'perm_clearing' => (bool)$contract->Contr_Perm_Clearing,
                'perm_card' => (bool)$contract->Contr_Perm_Card,
                'perm_cash' => (bool)$contract->Contr_Perm_Cash,
                'cash_control' => (bool)$contract->Contr_Cash_Control,
                'cash_limit' => $contract->Contr_Limit_Cash,
                'deferral' => $contract->Contr_Defferal,
                'credit_limit' => $contract->Contr_Credit_Limit,
                'have_original' => (bool)$contract->Contr_IHaveDocs,
                'accept' => (bool)$contract->Contr_Accept,
                'vat_rate' => $contract->Contr_Accept,
                'vat_include' => (bool)$contract->Contr_VAT_Include,
                'is_active' => !(bool)$contract->deletion_mark,
            ], 'guid_id');

            if ($result->getErrorCollection()->count()) {
                $error = $result->getErrorCollection()->current();
                $answer->addResponse(new ResponseObject($contract->Contr_ID, new MethodException($error->getMessage(), intval($error->getCode()))));
                $this->client->getLogger()->error($error->getMessage() . " (" . $error->getCode() . ")");
            } else {
                if ($result->getId()) {
                    if ($result instanceof UpdateResult) {
                        $this->client->getLogger()->notice("Обновлена запись Договора {$result->getId()} ({$contract->Contr_ID})");
                    } else {
                        $this->client->getLogger()->notice("Добавлена запись Договора {$result->getId()} ({$contract->Contr_ID})");
                    }
                    $answer->addResponse(new ResponseObject($contract->Contr_ID));
                } else {
                    $answer->addResponse(new ResponseObject($contract->Contr_ID, new MethodException(self::$default_errors[self::ERROR_UNDEFINED], self::ERROR_UNDEFINED)));
                    $this->client->getLogger()->error(self::$default_errors[self::ERROR_UNDEFINED] . " (" . self::ERROR_UNDEFINED . ")");
                }
            }
        }
        $this->contractAnswer($answer);
        return count($answer->Response);
    }
}