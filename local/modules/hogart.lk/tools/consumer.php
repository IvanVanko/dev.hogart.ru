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

$lock = new \Liip\ProcessManager\PidFile(new \Liip\ProcessManager\ProcessManager(), __DIR__ . "/consumer.pid");
$lock->acquireLock();

$consumer = \Hogart\Lk\Exchange\RabbitMQ\Consumer::getInstance();
$consumer->registerLogger(new \Hogart\Lk\Logger\FileLogger(__DIR__ . "/../logs/rabbitmq.log"));
$consumer->setIsCliContext(true);

$consumer->registerExchange([
    new \Hogart\Lk\Exchange\RabbitMQ\Exchange\AccountExchange(),
    new \Hogart\Lk\Exchange\RabbitMQ\Exchange\AddressExchange(),
    new \Hogart\Lk\Exchange\RabbitMQ\Exchange\AddressTypeExchange(),
    new \Hogart\Lk\Exchange\RabbitMQ\Exchange\CompanyExchange(),
    new \Hogart\Lk\Exchange\RabbitMQ\Exchange\CompanyDiscountExchange(),
    new \Hogart\Lk\Exchange\RabbitMQ\Exchange\ContactExchange(),
    new \Hogart\Lk\Exchange\RabbitMQ\Exchange\ContactInfoExchange(),
    new \Hogart\Lk\Exchange\RabbitMQ\Exchange\ContractExchange(),
    new \Hogart\Lk\Exchange\RabbitMQ\Exchange\HogartCompanyExchange(),
    new \Hogart\Lk\Exchange\RabbitMQ\Exchange\OrderExchange(),
    new \Hogart\Lk\Exchange\RabbitMQ\Exchange\OrderRTUExchange(),
    new \Hogart\Lk\Exchange\RabbitMQ\Exchange\PdfExchange(),
    new \Hogart\Lk\Exchange\RabbitMQ\Exchange\PaymentExchange(),
    new \Hogart\Lk\Exchange\RabbitMQ\Exchange\PaymentAccountExchange(),
    new \Hogart\Lk\Exchange\RabbitMQ\Exchange\RTUExchange(),
    new \Hogart\Lk\Exchange\RabbitMQ\Exchange\StaffExchange(),
    new \Hogart\Lk\Exchange\RabbitMQ\Exchange\CurrencyRateExchange(),
]);

while (true) {
    $consumer->run();
    sleep(5);
}

$lock->releaseLock();
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");