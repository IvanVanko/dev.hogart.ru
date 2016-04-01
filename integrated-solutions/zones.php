<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Зоны");

$APPLICATION->IncludeComponent("kontora:section.detail", "zones", array(
  'SECTION_CODE' => $_REQUEST['zone'],
  'FILTER' => array('IBLOCK_ID' => 17),
  'SELECT' => array('ID', 'IBLOCK_ID', 'NAME', 'DESCRIPTION', 'UF_PROJECTS'),
));?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>