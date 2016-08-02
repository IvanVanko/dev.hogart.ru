<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 02/08/16
 * Time: 11:32
 */

namespace Hogart\Lk\Exchange\SOAP;


abstract class AbstractMethod implements MethodInterface
{
    /** @var  \SoapClient */
    protected $client;

    /**
     * @inheritDoc
     */
    function useSoapClient(\SoapClient $client)
    {
        $this->client = $client;

        return $this;
    }

}