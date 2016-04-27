<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Success stories");
$APPLICATION->SetTitle("Success stories");

$APPLICATION->IncludeComponent(
	"kontora:element.list",
	"history",
	Array(
		"IBLOCK_ID" => 42,
		'ORDER'     => array('sort' => 'asc'),
		'PROPS'     => 'Y',
));
?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>