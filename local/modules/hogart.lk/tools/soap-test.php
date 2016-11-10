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
ini_set("xdebug.var_display_max_depth", -1);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule("hogart.lk");
CModule::IncludeModule("main");
CModule::IncludeModule("catalog");
CModule::IncludeModule("sprint.migration");

//$helper = new \Sprint\Migration\Helpers\IblockHelper();
//$helper->addPropertyIfNotExists(CATALOG_IBLOCK_ID, [
//    'NAME' => 'Кратность отгрузки',
//    'CODE' => 'default_count',
//    'FILTRABLE' => 'N',
//]);
//exit;

//\Hogart\Lk\Entity\AccountTable::sendNewAccountPassword('v.sokolov@hogart.ru');
//exit;
//
//require($_SERVER["DOCUMENT_ROOT"]."/k_1c_upload/ParsingModel.php");
//$parse = new ParsingModel(false);

//$answer = [
//    "ID_Portal" => "HG",
//    "StringStock" => "9e851ffa-3976-11e4-b439-003048b99ee9"
//    "StringBrands" => "9e851ffa-3976-11e4-b439-003048b99ee9"
//];

//try {
//    $parse->client->__soapCall("StockAnswer", array('parameters' => $answer));
//    $parse->client->__soapCall("BrandAnswer", array('parameters' => $answer));
//} catch (Exception $e) {
//    var_dump($e);
//}


//$parse->answer = true;
//while (true) {
//    $parse->initCategory();
//    $parse->initProduct();
//    ob_end_flush();
//    flush();
//    sleep(1);
//}
//$parse->initBranch();
//$parse->initWarehouse();
//var_dump($parse->GetResultFunction('StockGet'));
//exit;
/** @var \Hogart\Lk\Exchange\SOAP\Client $soap */
$soap = \Hogart\Lk\Exchange\SOAP\Client::getInstance();

$soap->getLogger()->registerLogger(new \Hogart\Lk\Logger\FileLogger(__DIR__ . "/../logs/soap.log"));

//$c = new \Hogart\Lk\Exchange\SOAP\Request\Contact([\Hogart\Lk\Entity\ContactTable::getRowById(7)]);
//var_dump($c->__toRequest());
//var_dump($soap->Contact->contactsPut($c));
//exit;
//var_dump($soap->Contract->getContract());

//$o = new \Hogart\Lk\Exchange\SOAP\Request\OrderRTU([\Hogart\Lk\Entity\OrderRTUTable::getRTUOrder(1)]);
//var_dump($o->__toRequest());
//var_dump($soap->OrderRTU->orderRTUPut($o));
//var_dump($soap->Payment->updatePayments());
//var_dump($soap->Contract->contractPut(new \Hogart\Lk\Exchange\SOAP\Request\Contract(\Hogart\Lk\Entity\ContractTable::getContractForExchange(2))));

//var_dump(\Hogart\Lk\Entity\OrderTable::getOrder(9));
//exit;
//var_dump($soap->Orders->ordersPut(new \Hogart\Lk\Exchange\SOAP\Request\Order([\Hogart\Lk\Entity\OrderTable::getOrder(9)])));

//$request = new \Hogart\Lk\Exchange\SOAP\Request\OrderRTU([\Hogart\Lk\Entity\OrderRTUTable::getRTUOrder(7)]);
//var_dump($request->__toRequest());

//var_dump($soap->OrderRTU->orderRTUPut(new \Hogart\Lk\Exchange\SOAP\Request\OrderRTU([\Hogart\Lk\Entity\OrderRTUTable::getRTUOrder(1)])));
//$addressRequest = new \Hogart\Lk\Exchange\SOAP\Request\Address([\Hogart\Lk\Entity\AddressTable::getRowById(["guid_id" => "f07b8268-2492-5d54-aaba-111235682d05", "owner_id" => 2, "owner_type" => 2])]);
//var_dump($addressRequest->__toRequest());
//var_dump($soap->Address->addressPut($addressRequest));
//var_dump($soap->AddressType->updateAddressTypes());
//var_dump($soap->Address->updateAddresses());
//var_dump($soap->Orders->ordersGet());
//var_dump($soap->Orders->setIsAnswer(false)->updateOrders());
//var_dump($soap->OrderRTU->updateOrdersRTU());
//var_dump($soap->CompanyDiscount->getCompanyDiscounts());
var_dump($soap->CompanyDiscount->updateCompanyDiscounts());
//var_dump($soap->RTU->updateRTUs());

//$companyRequest = new \Hogart\Lk\Exchange\SOAP\Request\Company([\Hogart\Lk\Entity\CompanyTable::getRowById(8)]);
//var_dump($companyRequest->__toRequest());
//var_dump($soap->Company->companyPut($companyRequest));

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