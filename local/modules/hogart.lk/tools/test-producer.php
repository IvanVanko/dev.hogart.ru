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

$producer = new \Hogart\Lk\Exchange\RabbitMQ\Producer(
    COption::GetOptionString("hogart.lk", "RABBITMQ_HOST"),
    COption::GetOptionString("hogart.lk", "RABBITMQ_PORT"),
    COption::GetOptionString("hogart.lk", "RABBITMQ_LOGIN"),
    COption::GetOptionString("hogart.lk", "RABBITMQ_PASSWORD")
);

$staff = new \Hogart\Lk\Exchange\RabbitMQ\Exchange\StaffExchange();
$account = new \Hogart\Lk\Exchange\RabbitMQ\Exchange\AccountExchange();

$staff
    ->useProducer($producer)
    ->getExchange()
    ->publish("", "staff.update", AMQP_NOPARAM, ["delivery_mode" => 2]);

$account
    ->useProducer($producer)
    ->getExchange()
    ->publish("", "account.update", AMQP_NOPARAM, ["delivery_mode" => 2]);
