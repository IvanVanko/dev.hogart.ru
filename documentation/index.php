<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Поиск документации");
$APPLICATION->SetTitle("Документация");
?>

<?$APPLICATION->IncludeComponent("kontora:element.list", "documentation_brand_list", array(
    'IBLOCK_ID' => '10',
    'ORDER' => array('NAME' => 'ASC'),
    'SELECT' => array('NAME', 'PROPERTY_brand.ID', 'IBLOCK_ID', 'ID'),
    'PROPS' => 'Y',
));?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>