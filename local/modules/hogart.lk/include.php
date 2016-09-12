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

\Bitrix\Main\Page\Asset::getInstance()->addJs('//cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.5/validator.min.js', true);

\Bitrix\Main\Page\Asset::getInstance()->addCss('//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/css/jasny-bootstrap.min.css', true);
\Bitrix\Main\Page\Asset::getInstance()->addJs('//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/js/jasny-bootstrap.min.js', true);

\Bitrix\Main\Page\Asset::getInstance()->addCss('//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.0/css/bootstrap-select.min.css', true);
\Bitrix\Main\Page\Asset::getInstance()->addJs('//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.0/js/bootstrap-select.min.js', true);
\Bitrix\Main\Page\Asset::getInstance()->addJs('//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.0/js/i18n/defaults-ru_RU.min.js', true);

\Bitrix\Main\Page\Asset::getInstance()->addJs('/local/modules/hogart.lk/assets/hogart.lk/js/hogart.js', true);

\Bitrix\Main\Page\Asset::getInstance()->addJs('//cdnjs.cloudflare.com/ajax/libs/moment.js/2.14.1/moment.min.js', true);
\Bitrix\Main\Page\Asset::getInstance()->addJs('//cdnjs.cloudflare.com/ajax/libs/moment.js/2.14.1/locale/ru.js', true);

\Bitrix\Main\Page\Asset::getInstance()->addCss('/local/modules/hogart.lk/assets/bootstrap-switch/build.css', true);
\Bitrix\Main\Page\Asset::getInstance()->addJs('//cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.2/js/bootstrap-switch.min.js', true);