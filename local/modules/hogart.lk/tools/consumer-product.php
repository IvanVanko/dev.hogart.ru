#!/usr/bin/php
<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 30/07/16
 * Time: 22:35
 */
$_SERVER["DOCUMENT_ROOT"] = realpath(__DIR__ . "/../../../../");
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
define("ADMIN_SECTION", true);
set_time_limit(0);
ini_set('output_buffering', 'Off');
ini_set('implicit_flush', 1);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule("hogart.lk");

$lock = new \Liip\ProcessManager\PidFile(new \Liip\ProcessManager\ProcessManager(), __DIR__ . "/consumer-product.pid");
$lock->acquireLock();

$consumer = \Hogart\Lk\Exchange\RabbitMQ\Consumer::getInstance();
$consumer->registerLogger(new \Hogart\Lk\Logger\FileLogger(__DIR__ . "/../logs/rabbitmq.log"));
$consumer->setIsCliContext(true);

$consumer->registerExchange([
    new \Hogart\Lk\Exchange\RabbitMQ\Exchange\ProductExchange(),
]);

while (true) {
    $consumer->run();
    sleep(10);
}

$lock->releaseLock();
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");