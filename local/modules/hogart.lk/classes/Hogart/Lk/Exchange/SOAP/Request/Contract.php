<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 30/09/2016
 * Time: 13:50
 */

namespace Hogart\Lk\Exchange\SOAP\Request;


use Hogart\Lk\Exchange\SOAP\AbstractPutRequest;

class Contract extends AbstractPutRequest
{
    private $contracts = [];

    /**
     * Contract constructor.
     * @param array $contracts
     */
    public function __construct(array $contracts = [])
    {
        foreach ($contracts as $contract) {
            $this->contracts[] = (object)[
                "Contr_ID_Company" => $contract['co_guid_id'],
                "Contr_ID_Hogart" => $contract['hco_guid_id'],
                "Contr_ID" => $contract['guid_id'],
                "Contr_ID_Site" => $contract['id'],
                "Contr_Number" => $contract['number'],
                "Contr_Date" => $contract['start_date'] ? $contract['start_date']->format('Y-m-d') : null,
                "Contr_DateTO" => $contract['end_date'] ? $contract['end_date']->format('Y-m-d') : null,
                "Contr_Prolon" => (bool)$contract['extension'],
                "Contr_ID_Money" => $contract['currency_code'],
                "Contr_Perm_Item" => (bool)$contract['perm_item'],
                "Contr_Perm_Clearing" => (bool)$contract['perm_clearing'],
                "Contr_Perm_Card" => (bool)$contract['perm_card'],
                "Contr_Perm_Cash" => (bool)$contract['perm_cash'],
                "Contr_Cash_Control" => (bool)$contract['cash_control'],
                "Contr_Limit_Cash" => (float)$contract['cash_limit'],
                "Contr_Defferal" => (int)$contract['deferral'],
                "Contr_Credit_Limit" => (float)$contract['credit_limit'],
                "Contr_IHaveDocs" => (bool)$contract['have_original'],
                "Contr_Accept" => (bool)$contract['accept'],
                "Contr_VAT_Rate" => $contract['vat_rate'],
                "Contr_VAT_Include" => (bool)$contract['vat_include'],
                "deletion_mark" => !$contract['is_active'],
                "Contr_Perm_Promo" => (bool)$contract['perm_promo'],
                "Contr_ID_Account" => $contract['account_id'],
            ];
        }
    }

    protected function request()
    {
        return ['Contract' => $this->contracts];
    }
}