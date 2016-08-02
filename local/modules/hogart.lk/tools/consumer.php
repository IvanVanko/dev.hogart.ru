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
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule("hogart.lk");

$consumer = new \Hogart\Lk\Exchange\RabbitMQ\Consumer(
    COption::GetOptionString("hogart.lk", "RABBITMQ_HOST"),
    COption::GetOptionString("hogart.lk", "RABBITMQ_PORT"),
    COption::GetOptionString("hogart.lk", "RABBITMQ_LOGIN"),
    COption::GetOptionString("hogart.lk", "RABBITMQ_PASSWORD")
);

$consumer->registerLogger(new \Hogart\Lk\Logger\FileLogger(__DIR__ . "/../logs/rabbitmq.log"));

$consumer->registerExchange([
    $staff = new \Hogart\Lk\Exchange\RabbitMQ\Exchange\StaffExchange(),
    $account = new \Hogart\Lk\Exchange\RabbitMQ\Exchange\AccountExchange(),
]);

$consumer->run();

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");

