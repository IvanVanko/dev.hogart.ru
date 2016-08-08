<?php
/**
 * By: Ivan Kiselev aka shaqito[at]gmail.com
 * Via: PhpStorm.
 * At: 03.08.2016 13:32
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

//\Hogart\Lk\Entity\ContactInfoTable::dropTableIfExists();
//\Hogart\Lk\Entity\OrderTable::dropTableIfExists();
//\Hogart\Lk\Entity\OrderItemTable::dropTableIfExists();
//\Hogart\Lk\Entity\RTUItemTable::dropTableIfExists();

//\Hogart\Lk\Entity\ContactInfoTable::createTableIfNotExists();
//\Hogart\Lk\Entity\OrderTable::createTableIfNotExists();
//\Hogart\Lk\Entity\OrderItemTable::createTableIfNotExists();
//\Hogart\Lk\Entity\RTUItemTable::createTableIfNotExists();

/** @var \Hogart\Lk\Exchange\SOAP\Client $soap */
//$eml = 'ivan.kiselev@gmail.com';
//var_dump(\CUser::SendPassword($eml, $eml));

$soap = \Hogart\Lk\Exchange\SOAP\Client::getInstance();
$soap->getLogger()->registerLogger(new \Hogart\Lk\Logger\FileLogger(__DIR__ . "/../logs/soap2.log"));
$soap = \Hogart\Lk\Exchange\SOAP\Client::getInstance();
//print_r($soap->Company->setIsAnswer(false)->updateCompanies());
print_r($soap->CompanyDiscount->setIsAnswer(false)->updateCompanyDiscounts());

//print_r($soap->Account->setIsAnswer(false)->updateAccounts());
//print_r($soap->Staff->setIsAnswer(false)->updateStaff());
//print_r($soap->Contract->setIsAnswer(false)->updateContracts());
//print_r($soap->Orders->setIsAnswer(false)->updateOrders());
//print_r($soap->Company->setIsAnswer(false)->updateCompanies());
//print_r($soap->HogartCompany->setIsAnswer(false)->updateHogartCompanies());
//print_r($soap->Account->setIsAnswer(false)->updateAccounts());
//print_r($soap->Staff->setIsAnswer(false)->updateStaff());

//var_dump($soap->Contract->getContracts());

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