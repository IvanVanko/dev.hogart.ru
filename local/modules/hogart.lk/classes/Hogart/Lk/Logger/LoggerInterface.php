<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 31/07/16
 * Time: 03:11
 */

namespace Hogart\Lk\Logger;


interface LoggerInterface
{
    function error($message);
    function warning($message);
    function notice($message);
    function debug($message);
    function setService($service);
}