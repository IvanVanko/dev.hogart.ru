<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 03/08/16
 * Time: 15:40
 */

namespace Hogart\Lk\Exchange\SOAP;

abstract class AbstractResponseObject
{
    /** @var string */
    public $Error;
    /** @var  string */
    public $ErrorText;

    /**
     * ResponseObject constructor.
     * @param MethodException $e
     */
    public function __construct(MethodException $e = null)
    {
        if (null !== $e) {
            $this->setError($e);
        }
    }

    /**
     * @param MethodException $e
     * @return $this
     */
    public function setError(MethodException $e)
    {
        $this->Error = $e->getCode();
        $this->ErrorText = $e->getMessage();
        Client::getInstance()->getLogger()->error($this->ErrorText . " (" . $this->Error . ")");
        return $this;
    }
}
