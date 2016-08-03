<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 31/07/16
 * Time: 03:11
 */

namespace Hogart\Lk\Logger;


class BitrixLogger extends AbstractLogger
{
    function error($message)
    {
        $this->prepareMessage($message);
        return \CEventLog::Add(array(
            "SEVERITY" => "ERROR",
            "MODULE_ID" => "hogart.lk",
            "AUDIT_TYPE_ID" => $this->service,
            "DESCRIPTION" => $message,
        ));
    }

    function warning($message)
    {
        $this->prepareMessage($message);
        return \CEventLog::Add(array(
            "SEVERITY" => "WARNING",
            "MODULE_ID" => "hogart.lk",
            "AUDIT_TYPE_ID" => $this->service,
            "DESCRIPTION" => $message,
        ));
    }

    function notice($message)
    {
        return \CEventLog::Add(array(
            "SEVERITY" => "INFO",
            "MODULE_ID" => "hogart.lk",
            "AUDIT_TYPE_ID" => $this->service,
            "DESCRIPTION" => $message,
        ));
    }

    function debug($message)
    {
        $this->prepareMessage($message);
        return \CEventLog::Add(array(
            "SEVERITY" => "DEBUG",
            "MODULE_ID" => "hogart.lk",
            "AUDIT_TYPE_ID" => $this->service,
            "DESCRIPTION" => $message,
        ));
    }
}