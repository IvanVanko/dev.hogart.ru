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
use Hogart\Lk\Exchange\SOAP\AbstractPutRequest;
use Hogart\Lk\Exchange\SOAP\MethodException;
use Hogart\Lk\Exchange\SOAP\Request;
use Hogart\Lk\Exchange\SOAP\Response;
use Hogart\Lk\Exchange\SOAP\ResponseObject;

/**
 * Class Contract - добавление Договоров (они же Контракты)
 * @package Hogart\Lk\Exchange\SOAP\Method
 */
class Contract extends AbstractMethod
{
    /**
     * {@inheritDoc}
     */
    function getName()
    {
        return "Contract";
    }

    public function contractPut(AbstractPutRequest $request)
    {
        $response = $this->client->getSoapClient()->ContractPut($request->__toRequest());

        if (!empty($response->return->Error)) {
            $error = new MethodException(MethodException::ERROR_SOAP, [$response->return->ErrorText, $response->return->Error]);
            $this->client->getLogger()->error($error->getMessage());
            throw $error;
        }

        foreach ($response->return->Response as $contract) {
            $c = [
                'guid_id' => $contract->ID,
            ];
            if (!empty($contract->Contr_ID_Hogart)) {
                $hogart_company = HogartCompanyTable::getByField("guid_id", $contract->Contr_ID_Hogart);
                if (!empty($hogart_company)) {
                    $c['hogart_company_id'] = $hogart_company['id'];
                }
            }
            ContractTable::update($contract->ID_Site, $c);
        }
        return $response;
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

            $client_company = CompanyTable::getByField('guid_id', $contract->Contr_ID_Company);
            $hogart_company = HogartCompanyTable::getByField('guid_id', $contract->Contr_ID_Hogart);
            if (empty($hogart_company['id'])) {
                $answer->addResponse(new ResponseObject($contract->Contr_ID, new MethodException(MethodException::ERROR_NO_HOGART_COMPANY, [$contract->Contr_ID_Holding])));
                continue;
            }
            if (empty($client_company['id'])) {
                $answer->addResponse(new ResponseObject($contract->Contr_ID, new MethodException(MethodException::ERROR_NO_CLIENT_COMPANY, [$contract->Contr_ID_Company])));
                continue;
            }

            $result = ContractTable::createOrUpdateByField([
                'guid_id' => $contract->Contr_ID,
                'company_id' => $client_company['id'],
                'hogart_company_id' => $hogart_company['id'],
                'number' => (string)$contract->Contr_Number,
                'start_date' => new Date((string)$contract->Contr_Date, 'Y-m-d'),
                'end_date' => new Date((string)$contract->Contr_DateTO, 'Y-m-d'),
                'prolongation' => (bool)$contract->Contr_Prolon,
                'currency_code' => $contract->Contr_ID_Money,
                'perm_item' => (bool)$contract->Contr_Perm_Item,
                'perm_promo' => (bool)$contract->Contr_Perm_Promo,
                'perm_clearing' => (bool)$contract->Contr_Perm_Clearing,
                'perm_card' => (bool)$contract->Contr_Perm_Card,
                'perm_cash' => (bool)$contract->Contr_Perm_Cash,
                'cash_control' => (bool)$contract->Contr_Cash_Control,
                'cash_limit' => intval($contract->Contr_Limit_Cash),
                'deferral' => intval($contract->Contr_Defferal),
                'credit_limit' => intval($contract->Contr_Credit_Limit),
                'have_original' => (bool)$contract->Contr_IHaveDocs,
                'accept' => (bool)$contract->Contr_Accept,
                'vat_rate' => intval($contract->Contr_VAT_Rate),
                'vat_include' => (bool)$contract->Contr_VAT_Include,
                'is_active' => !(bool)$contract->deletion_mark,
            ], 'guid_id');

            if ($result->getErrorCollection()->count()) {
                $error = $result->getErrorCollection()->current();
                $answer->addResponse(new ResponseObject($contract->Contr_ID, new MethodException(MethodException::ERROR_BITRIX, [$error->getMessage(), $error->getCode()])));
            } else {
                if ($result->getId()) {
                    if ($result instanceof UpdateResult) {
                        $this->client->getLogger()->notice("Обновлена запись Договора {$result->getId()} ({$contract->Contr_ID})");
                    } else {
                        $this->client->getLogger()->notice("Добавлена запись Договора {$result->getId()} ({$contract->Contr_ID})");
                    }
                    $answer->addResponse(new ResponseObject($contract->Contr_ID));
                } else {
                    $answer->addResponse(new ResponseObject($contract->Contr_ID, new MethodException(MethodException::ERROR_UNDEFINED)));
                }
            }
        }
        $this->contractAnswer($answer);
        return count($answer->Response);
    }
}