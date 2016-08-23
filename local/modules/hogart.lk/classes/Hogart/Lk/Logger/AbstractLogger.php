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
    const STACK_FULL = 1;
    const STACK_LINE = 2;
    
    /** @var int  */
    protected $stackTraceLevel = self::STACK_LINE;
    /** @var int  */
    protected $stackTraceLines = 3;
    /** @var  string */
    protected $service;

    /**
     * AbstractLogger constructor.
     * @param string $service
     * @param null $stackTraceLevel
     * @param null $stackTraceLines
     */
    public function __construct($service = null, $stackTraceLevel = null, $stackTraceLines = null)
    {
        $this->service = $service;
        if (null !== $stackTraceLevel) {
            $this->stackTraceLevel = $stackTraceLevel;
            $this->stackTraceLines = $stackTraceLines;
        }
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
        if ($this->stackTraceLevel == self::STACK_LINE) {
            $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, $this->stackTraceLines);
            if (($error = end($backtrace))) {
                $message .= "\t{$error['file']}:{$error['line']}";
            }
        } else {
            ob_start();
            debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
            $backtrace = ob_get_clean();
            $message .= "\n\n" . $backtrace;
        }
    }
}
