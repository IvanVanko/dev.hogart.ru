<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 10/10/2016
 * Time: 12:32
 */

namespace Hogart\Lk\Exchange\SOAP;


use SuperClosure\SerializableClosure;

class LazyRequest
{
    /** @var SerializableClosure  */
    protected $callable;
    /** @var array  */
    protected $arguments = [];

    /**
     * LazyRequest constructor.
     * @param callable $callable
     * @param array $arguments
     */
    public function __construct(callable $callable, array $arguments)
    {
        $this->callable = new SerializableClosure($callable);
        $this->arguments = $arguments;
    }

    public function execute()
    {
        return call_user_func_array($this->callable->getClosure(), $this->arguments);
    }
}
