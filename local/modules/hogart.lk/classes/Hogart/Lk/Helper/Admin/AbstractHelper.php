<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 17/10/2016
 * Time: 11:02
 */

namespace Hogart\Lk\Helper\Admin;


abstract class AbstractHelper
{
    private function getMethod($method){
        $path = explode('\\', $method);
        $short = array_pop($path);
        return $short;
    }

    protected function checkRequiredKeys($method, $fields, $reqKeys = array()){
        foreach ($reqKeys as $name){
            if (!isset($fields[$name])){
                $msg = sprintf('%s: requred key "%s" not found', $this->getMethod($method), $name);
                Throw new \Exception($msg);
            }
        }
    }

    protected function throwException($method, $msg, $var1 = null, $var2 = null) {
        $args = func_get_args();
        $method = array_shift($args);
        $msg = call_user_func_array('sprintf', $args);

        $msg = $this->getMethod($method) . ': ' . strip_tags($msg);

        $this->lastError = $msg;

        Throw new \Exception($msg);
    }
}