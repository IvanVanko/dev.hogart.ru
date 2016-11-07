<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 27/09/2016
 * Time: 03:40
 */

namespace Hogart\Lk\Exchange\SOAP;


abstract class AbstractPutRequest
{
    /**
     * @return Request
     */
    public function __toRequest()
    {
        $request = new Request();
        $request->Data = $this->proceedFields($this->request());
        return $request;
    }

    protected function proceedFields($object)
    {
        foreach ($object as &$value) {
            if ($value instanceof LazyRequest) {
                $value = $value->execute();
            }
            if (is_array($value) || is_object($value)) {
                $value = $this->proceedFields($value);
            }
        }

        return $object;
    }

    abstract protected function request();
}