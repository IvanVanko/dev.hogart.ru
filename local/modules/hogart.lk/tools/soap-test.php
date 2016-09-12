<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 02/08/16
 * Time: 12:24
 */
$_SERVER["DOCUMENT_ROOT"] = realpath(__DIR__ . "/../../../../");
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
define("ADMIN_SECTION", true);
set_time_limit(0);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule("hogart.lk");
CModule::IncludeModule("main");
CModule::IncludeModule("catalog");

/** @var \Hogart\Lk\Exchange\SOAP\Client $soap */
$soap = \Hogart\Lk\Exchange\SOAP\Client::getInstance();

$soap->getLogger()->registerLogger(new \Hogart\Lk\Logger\FileLogger(__DIR__ . "/../logs/soap.log"));

ini_set("xdebug.var_display_max_depth", -1);
var_dump($soap->Contract->getContract());

//$response = new \Hogart\Lk\Exchange\SOAP\Method\Response();
//$response->addResponse(new \Hogart\Lk\Exchange\SOAP\Method\ResponseObject("25dcd338-57d2-11e6-82d6-00155d000a02"));

//$soap->Address->updateAddresses();
//print_r($soap->Address->getAddresses());
//var_dump($answer);
//print_r($soap->Account->getAccounts());
//$soap->Account->accountAnswer($answer);
//$soap->Account->createOrUpdateAccounts();
//echo $soap->getSoapClient()->__getLastRequest() . "\n";


require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");