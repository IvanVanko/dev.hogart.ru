<?php
/**
 * By: Ivan Kiselev aka shaqito[at]gmail.com
 * Via: PhpStorm.
 * At: 03.08.2016 16:36
 */

namespace Hogart\Lk\Exchange\SOAP\Method;

use Hogart\Lk\Exchange\SOAP\AbstractMethod;
use Hogart\Lk\Entity\PaymentAccountTable;
use Hogart\Lk\Entity\PaymentAccountRelationTable;
use Hogart\Lk\Entity\CompanyTable;
use Hogart\Lk\Entity\HogartCompanyTable;
use Bitrix\Main\Entity\UpdateResult;
use Hogart\Lk\Exchange\SOAP\MethodException;
use Hogart\Lk\Exchange\SOAP\Request;
use Hogart\Lk\Exchange\SOAP\Response;
use Hogart\Lk\Exchange\SOAP\ResponseObject;

class PaymentAccount extends AbstractMethod
{
    /**
     * {@inheritDoc}
     */
    function getName()
    {
        return "PaymentAccount";
    }

    public function getPaymentAccounts()
    {
        return $this->client->getSoapClient()->PaymentAccountsGet(new Request());
    }

    public function paymentAccountAnswer(Response $response)
    {
        if (count($response->Response) && $this->is_answer) {
            return $this->client->getSoapClient()->PaymentAccountsAnswer($response);
        }
    }

    public function updatePaymentAccounts()
    {
        $answer = new Response();
        $response = $this->getPaymentAccounts();

        foreach ($response->return->Payment_Account as $payment_account) {
            // забираем компанию и устанавливаем тип связи аккаунта
            $owner_type = PaymentAccountRelationTable::OWNER_TYPE_CLIENT_COMPANY;
            $company = CompanyTable::getByField('guid_id', $payment_account->PayAc_ID_Company);
            if(!$company) {
                $owner_type = PaymentAccountRelationTable::OWNER_TYPE_HOGART_COMPANY;
                $company = HogartCompanyTable::getByField('guid_id', $payment_account->PayAc_ID_Company);
                if (empty($company['id'])) {
                    $answer->addResponse(new ResponseObject($payment_account->PayAc_ID, new MethodException(MethodException::ERROR_NO_ANY_COMPANY, [$payment_account->PayAc_ID_Company])));
                    continue;
                }
            }

            // данные по Расчетному счету
            $result = PaymentAccountTable::createOrUpdateByField([
                'guid_id' => $payment_account->PayAc_ID,
                'number' => $payment_account->PayAc_Number,
                'currency_code' => $payment_account->PayAc_ID_Money,
                'bik' => $payment_account->PayAc_BIK,
                'bank_name' => $payment_account->PayAc_BankName,
                'corr_number' => $payment_account->PayAc_CorrNumber,
                'is_active' => !$payment_account->deletion_mark,
            ], 'guid_id');


            if ($result->getErrorCollection()->count()) {
                $error = $result->getErrorCollection()->current();
                $answer->addResponse(new ResponseObject($payment_account->PayAc_ID, new MethodException(MethodException::ERROR_BITRIX, [$error->getMessage(), $error->getCode()])));
            } else {
                if ($result->getId()) {
                    // связь Расчетного счета и Компании Клиента||Хогарта
                    $resultRelation = PaymentAccountRelationTable::replace([
                        'payment_account_id' => $result->getId(),
                        'owner_id' => $company['id'],
                        'owner_type' => $owner_type,
                        'is_main' => $payment_account->PayAc_Main
                    ]);
                    if (!empty($resultRelation->getId())) {
                        $this->client->getLogger()->notice("Обновлена связь Расчетного счета ({$result->getId()}) и Компании ".
                            ($owner_type == PaymentAccountRelationTable::OWNER_TYPE_CLIENT_COMPANY ? 'Клиента' : 'Хогарт')
                            ." ({$company['id']})");
                    }

                    if ($result instanceof UpdateResult) {
                        $this->client->getLogger()->notice("Обновлена запись Расчетного счета {$result->getId()} ({$payment_account->PayAc_ID})");
                    } else {
                        $this->client->getLogger()->notice("Добавлена запись Расчетного счета {$result->getId()} ({$payment_account->PayAc_ID})");
                    }
                    $answer->addResponse(new ResponseObject($payment_account->PayAc_ID));
                } else {
                    $answer->addResponse(new ResponseObject($payment_account->PayAc_ID, new MethodException(MethodException::ERROR_UNDEFINED)));
                }
            }
        }
        $this->paymentAccountAnswer($answer);
        return count($answer->Response);
    }

}