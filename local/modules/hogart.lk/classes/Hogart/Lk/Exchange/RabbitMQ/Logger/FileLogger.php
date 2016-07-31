<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 31/07/16
 * Time: 03:52
 */

namespace Hogart\Lk\Exchange\RabbitMQ\Logger;


class FileLogger implements LoggerInterface
{
    const SEVERITY_NOTICE = "NOTICE";
    const SEVERITY_ERROR = "ERROR";
    const SEVERITY_DEBUG = "DEBUG";
    const SEVERITY_WARNING = "WARNING";

    protected $fileResource;

    /**
     * FileLogger constructor.
     * @param string $filePath
     */
    public function __construct($filePath)
    {
        if(!file_exists(dirname($filePath))) {
            mkdir(dirname($filePath), 0777, true);
        }

        $this->fileResource = fopen($filePath, "a");
    }

    public function log($message, $severity){
        $log = "";
        $log .= "[{$severity}]\t";
        $log .= "[" . date("D M d H:i:s Y", time()) . "]\t";
        $log .= $message;
        $log .= "\n";
        $this->_write($log);
    }

    protected function _write($string){
        if (!empty($this->fileResource)) {
            fwrite($this->fileResource, $string);
        }
    }

    public function __destruct(){
        fclose($this->fileResource);
    }

    function error($message)
    {
        $this->log($message, self::SEVERITY_ERROR);
    }

    function warning($message)
    {
        $this->log($message, self::SEVERITY_WARNING);
    }

    function notice($message)
    {
        $this->log($message, self::SEVERITY_NOTICE);
    }

    function debug($message)
    {
        $this->log($message, self::SEVERITY_DEBUG);
    }

}