<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 27/07/16
 * Time: 20:17
 */
/** @var Composer\Autoload\ClassLoader $classloader */
$classloader = require_once "vendor/autoload.php";
$classloader->add("Hogart\\Lk", __DIR__ . '/classes');

CModule::IncludeModule("main");
CModule::IncludeModule("catalog");