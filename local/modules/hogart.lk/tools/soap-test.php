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

//var_dump($soap->Contract->getContract());

//var_dump($soap->Payment->updatePayments());
//var_dump($soap->Contract->contractPut(new \Hogart\Lk\Exchange\SOAP\Request\Contract(\Hogart\Lk\Entity\ContractTable::getContractForExchange(2))));
//var_dump($soap->Orders->ordersPut(new \Hogart\Lk\Exchange\SOAP\Request\Order([\Hogart\Lk\Entity\OrderTable::getOrder(14)])));
//var_dump($soap->OrderRTU->orderRTUPut(new \Hogart\Lk\Exchange\SOAP\Request\OrderRTU([\Hogart\Lk\Entity\OrderRTUTable::getRowById(1)])));
//var_dump($soap->Address->addressPut(new \Hogart\Lk\Exchange\SOAP\Request\Address([\Hogart\Lk\Entity\AddressTable::getRowById(["guid_id" => "8d907286-fc4c-5b8b-a602-e2620102661b", "owner_id" => 1, "owner_type" => 2])])));

var_dump($soap->Address->updateAddresses());

//var_dump(new \Bitrix\Main\Type\DateTime("2016-10-03T13:26:23", 'Y-m-d H:i:s'));

//var_dump($soap->Orders->ordersPut(new \Hogart\Lk\Exchange\SOAP\Request\Order([\Hogart\Lk\Entity\OrderTable::getOrder(5)])));

//\Hogart\Lk\Entity\OrderTable::publishToRabbit(
//    new \Hogart\Lk\Exchange\RabbitMQ\Exchange\OrderExchange(),
//    new \Hogart\Lk\Exchange\SOAP\Request\Order([\Hogart\Lk\Entity\OrderTable::getOrder(1), \Hogart\Lk\Entity\OrderTable::getOrder(2), \Hogart\Lk\Entity\OrderTable::getOrder(3)])
//);

//var_dump(new \Hogart\Lk\Exchange\SOAP\Request\Order([\Hogart\Lk\Entity\OrderTable::getOrder(5)]));
exit;

//$contacts = \Hogart\Lk\Entity\ContactRelationTable::getContactsByOwner(18, \Hogart\Lk\Entity\ContactRelationTable::OWNER_TYPE_CLIENT_COMPANY);
//var_dump($soap->Contact->contactsPut($contacts));

//$companies = \Hogart\Lk\Entity\CompanyTable::getList([
//    'filter' => [
//        '=id' => 18
//    ],
//    'select' => [
//        '*',
//        'k_' => 'kind_activity',
//        'account_guid' => '\Hogart\Lk\Entity\AccountCompanyRelationTable:company.account.user_guid_id'
//    ]
//])->fetchAll();

//var_dump($soap->Company->companyPut($companies));

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