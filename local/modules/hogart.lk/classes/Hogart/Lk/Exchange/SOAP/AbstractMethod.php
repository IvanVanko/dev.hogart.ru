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
    /** @var  Client */
    protected $client;
    /** @var bool  */
    protected $is_answer = true;

    /**
     * {@inheritDoc}
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
