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

$consumer = \Hogart\Lk\Exchange\RabbitMQ\Consumer::getInstance();
//$accountExchange = new \Hogart\Lk\Exchange\RabbitMQ\Exchange\AccountExchange($consumer);

$currencyExchange = new \Hogart\Lk\Exchange\RabbitMQ\Exchange\CurrencyRateExchange($consumer);
$currencyExchange
    ->publish("", "get")
;

//var_dump($currencyExchange->getQueue()->get());

//$consumer->registerExchange([
//    new \Hogart\Lk\Exchange\RabbitMQ\Exchange\AccountExchange(),
//    new \Hogart\Lk\Exchange\RabbitMQ\Exchange\AddressExchange(),
//    new \Hogart\Lk\Exchange\RabbitMQ\Exchange\CompanyExchange(),
//    new \Hogart\Lk\Exchange\RabbitMQ\Exchange\CompanyDiscountExchange(),
//    new \Hogart\Lk\Exchange\RabbitMQ\Exchange\ContactExchange(),
//    new \Hogart\Lk\Exchange\RabbitMQ\Exchange\ContactInfoExchange(),
//    new \Hogart\Lk\Exchange\RabbitMQ\Exchange\ContractExchange(),
//    new \Hogart\Lk\Exchange\RabbitMQ\Exchange\HogartCompanyExchange(),
//    new \Hogart\Lk\Exchange\RabbitMQ\Exchange\OrderDocsExchange(),
//    new \Hogart\Lk\Exchange\RabbitMQ\Exchange\OrderExchange(),
//    new \Hogart\Lk\Exchange\RabbitMQ\Exchange\PaymentAccountExchange(),
//    new \Hogart\Lk\Exchange\RabbitMQ\Exchange\StaffExchange(),
//    new \Hogart\Lk\Exchange\RabbitMQ\Exchange\CurrencyRateExchange(),
//]);
//
//var_dump(array_keys($consumer->sortExchanges()));

//$accountExchange
//    ->getExchange()
//    ->publish("gillbeits@gmail.com", $accountExchange->getPublishKey("send_password"), AMQP_NOPARAM, ["delivery_mode" => 2])
//;

//$contract = new \Hogart\Lk\Exchange\RabbitMQ\Exchange\ContractExchange();
//
//$contract
//    ->useConsumer($consumer)
//    ->getExchange()
//    ->publish("", $contract->getPublishKey("get"), AMQP_NOPARAM, ["delivery_mode" => 2]);
