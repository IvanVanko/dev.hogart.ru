<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Объявление детально");
?>

<?=$_REQUEST["SECTION_CODE"];?>
<?
$ElementID = $APPLICATION->IncludeComponent("kontora:element.detail", "", array(
		"ID"    => $_REQUEST["CID"],
		'PROPS' => 'Y',
	),
	$component
);
?>