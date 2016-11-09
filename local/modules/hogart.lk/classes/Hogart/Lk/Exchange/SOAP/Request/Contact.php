<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 27/09/2016
 * Time: 11:52
 */

namespace Hogart\Lk\Exchange\SOAP\Request;


use Hogart\Lk\Exchange\SOAP\AbstractPutRequest;

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
                'Cont_ID_Company' => (string)$contact['company_guid_id'],
                'Cont_ID' => $contact['guid_id'],
                'Cont_ID_Site' => $contact['id'],
                'Cont_Surname' => $contact['last_name'],
                'Cont_Name' => $contact['name'],
                'Cont_Middle_Name' => $contact['middle_name'],
                'deletion_mark' => !$contact['is_active'],
                'Cont_Post' => (string)$contact['post'],
                'Comp_ID_Account' => $contact['a_id']
            ];
        }
    }

    protected function request()
    {
        return ['Contact' => $this->contacts];
    }
}