<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 10/10/2016
 * Time: 19:56
 */

namespace Hogart\Lk\Exchange\SOAP\Request;


use Hogart\Lk\Entity\PdfTable;
use Hogart\Lk\Exchange\SOAP\AbstractPutRequest;

class Pdf extends AbstractPutRequest
{
    /** @var  string */
    public $ID;
    /** @var  string */
    public $type;

    /**
     * Request constructor.
     * @param string $ID
     * @param string $type
     */
    public function __construct($ID, $type)
    {
        $this->ID = $ID;
        $this->type = $type;
    }

    public function request()
    {
        return (object)[
            'ID' => $this->ID,
            'ItIsBill' => $this->type == PdfTable::TYPE_BILL
        ];
    }
}
