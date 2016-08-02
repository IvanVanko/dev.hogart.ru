<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 02/08/16
 * Time: 11:32
 */

namespace Hogart\Lk\Exchange\SOAP;


interface MethodInterface
{
    /**
     * @param \SoapClient $client
     * @return $this
     */
    function useSoapClient(\SoapClient $client);

    /**
     * @return string
     */
    function getName();
}