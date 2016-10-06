<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 27/09/2016
 * Time: 11:39
 */

namespace Hogart\Lk\Exchange\SOAP\Request;


use Hogart\Lk\Exchange\SOAP\AbstractPutRequest;

class Company extends AbstractPutRequest
{
    /**
     * @var array
     */
    private $companies;

    /**
     * Company constructor.
     * @param array $companies
     */
    public function __construct($companies = [])
    {
        $this->companies = $companies;
    }

    protected function request()
    {
        $companies = [];
        $kinds_of_activities = [];
        foreach ($this->companies as $company) {
            if (!empty($company['k_guid_id'])) {
                $kinds_of_activities[] = (object)[
                    'ID' => $company['k_guid_id'],
                    'Name' => $company['k_name'],
                    'Description' => $company['k_description'],
                ];
            }
            $companies[] = (object)[
                'Comp_ID_Account' => $company['account_guid'],
                'Comp_ID' => $company['guid_id'],
                'Comp_ID_Site' => $company['id'],
                'Comp_Name' => $company['name'],
                'Comp_OOOIPFL' => $company['type'],
                'Comp_OOO_type' => $company['type_form'],
                'Comp_ID_KindOfActivity' => $company['k_guid_id'],
                'Comp_INN' => $company['inn'],
                'Comp_KPP' => $company['kpp'],
                'Comp_DataFactAddress' => $company['date_fact_address'] ? $company['date_fact_address']->format('Y-m-d') : null,
                'Comp_ID_Chief' => $company['chief_contact_guid_id'],
                'Comp_Certificate_Number' => $company['certificate_number'],
                'Comp_Certificate_Date' => $company['certificate_date'] ? $company['certificate_date']->format('Y-m-d') : null,
                'Comp_DocPass' => $company['doc_pass'],
                'Comp_DocSerial' => $company['doc_serial'],
                'Comp_DocNumber' => $company['doc_number'],
                'Comp_DocUFMS' => $company['doc_ufms'],
                'Comp_DocDate' => $company['doc_date'],
                'deletion_mark' => !$company['is_active'],
            ];
        }

        return ['Company' => $companies, 'KindsOfActivity' => $kinds_of_activities];
    }
}