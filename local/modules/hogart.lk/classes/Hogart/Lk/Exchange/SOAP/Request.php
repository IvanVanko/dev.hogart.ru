<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 02/08/16
 * Time: 12:33
 */

namespace Hogart\Lk\Exchange\SOAP;


class Request
{
    const PORTAL = "HG";

    /** @var  string */
    public $ID_Portal;

    /**
     * Request constructor.
     * @param $ID_Portal
     */
    public function __construct($ID_Portal = self::PORTAL)
    {
        $this->ID_Portal = $ID_Portal;
    }
}