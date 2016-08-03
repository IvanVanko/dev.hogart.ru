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
    const ERROR_UNDEFINED = 1;

    protected static $default_errors = [
        self::ERROR_UNDEFINED => "Неизвестная ошибка"
    ];

    /** @var  Client */
    protected $client;

    /**
     * @inheritDoc
     */
    function useSoapClient(Client $client)
    {
        $this->client = $client;

        return $this;
    }

}