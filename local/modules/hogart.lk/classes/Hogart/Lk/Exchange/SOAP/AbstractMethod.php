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
        self::ERROR_UNDEFINED => "Неизвестная ошибка",
    ];

    /** @var  Client */
    protected $client;
    /** @var bool  */
    protected $is_answer = true;

    protected static $errors = [

    ];

    /**
     * @param $code
     * @param array $args
     * @return string
     */
    public function getError($code, $args = [])
    {
        return vsprintf(static::$errors[$code], $args);
    }

    /**
     * @inheritDoc
     */
    function useSoapClient(Client $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isIsAnswer()
    {
        return $this->is_answer;
    }

    /**
     * @param boolean $is_answer
     * @return $this
     */
    public function setIsAnswer($is_answer)
    {
        $this->is_answer = $is_answer;
        return $this;
    }
}
