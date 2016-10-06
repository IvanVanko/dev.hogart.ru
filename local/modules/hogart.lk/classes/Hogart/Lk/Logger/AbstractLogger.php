<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 02/08/16
 * Time: 15:30
 */

namespace Hogart\Lk\Logger;


abstract class AbstractLogger implements LoggerInterface
{
    /** @var  string */
    protected $service;

    /**
     * AbstractLogger constructor.
     * @param string $service
     */
    public function __construct($service = null)
    {
        $this->service = $service;
    }

    /**
     * @return string
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param string $service
     * @return $this
     */
    public function setService($service)
    {
        $this->service = $service;
        return $this;
    }

    protected function prepareMessage(&$message)
    {
        ob_start();
        debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $backtrace = ob_get_clean();
        $message .= "\n\n" . $backtrace;
    }
}
