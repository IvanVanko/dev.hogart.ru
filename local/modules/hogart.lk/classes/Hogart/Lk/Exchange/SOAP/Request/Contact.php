<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 27/09/2016
 * Time: 11:52
 */

namespace Hogart\Lk\Exchange\SOAP\Request;


use Hogart\Lk\Entity\CompanyTable;
use Hogart\Lk\Entity\ContactTable;
use Hogart\Lk\Entity\HogartCompanyTable;
use Hogart\Lk\Exchange\SOAP\AbstractPutRequest;
use Hogart\Lk\Exchange\SOAP\LazyRequest;

class Contact extends AbstractPutRequest
{
    /**
     * @var array
     */
    private $contacts;

    /**
     * Contact constructor.
     * @param array $contacts
     */
    public function __construct($contacts = [])
    {
        foreach ($contacts as $contact) {
            $this->contacts[] = (object)[
                'Cont_ID_Company' => new LazyRequest(function ($contact) {
                    if (!empty($contact['co_id'])) {
                        return (string)CompanyTable::getRowById($contact['co_id'])['guid_id'];
                    }
                    if (!empty($contact['hco_id'])) {
                        return (string)HogartCompanyTable::getRowById($contact['hco_id'])['guid_id'];
                    }
                    return "";
                }, [$contact]),
                'Cont_ID' => $contact['guid_id'],
                'Cont_ID_Site' => $contact['id'],
                'Cont_Surname' => $contact['last_name'],
                'Cont_Name' => $contact['name'],
                'Cont_Middle_Name' => $contact['middle_name'],
                'deletion_mark' => !$contact['is_active'],
                'Cont_Post' => (string)$contact['post'],
                'Comp_ID_Account' => (string)$contact['a_user_guid_id']
            ];
        }
    }

    protected function request()
    {
        return ['Contact' => $this->contacts];
    }
}