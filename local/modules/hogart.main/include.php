<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 24/08/16
 * Time: 14:51
 */
//autoload psr-0
spl_autoload_register(function ($className) {
    $includeNamespace = 'Hogart\\Main';
    $includePath = __DIR__ . '/classes';

    if ($includeNamespace . '\\' === substr($className, 0, strlen($includeNamespace . '\\'))) {
        $fileName = '';
        if (false !== ($lastNsPos = strripos($className, '\\'))) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

        $fileName = ($includePath !== null ? $includePath . DIRECTORY_SEPARATOR : '') . $fileName;
        if (is_readable($fileName)) {
            /** @noinspection PhpIncludeInspection */
            require $fileName;
        }
    }
});