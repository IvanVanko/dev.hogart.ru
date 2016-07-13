<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Архив семинаров");
$APPLICATION->SetTitle("Архив семинаров");
?>

<?$APPLICATION->IncludeComponent("bitrix:news", "archives", array(
    'IBLOCK_ID' => '8',
    "FILTER_NAME"   => "filter",
    'SEF_MODE' => 'Y',
    "SEF_FOLDER" => "/learn/archive-seminarov/",
    "SEF_URL_TEMPLATES" => Array(
        "detail" => "#ELEMENT_CODE#/",
        "news" => "/learn/archive-seminarov/",
    ),
    'ORDER' => array('PROPERTY_sem_start_date' => 'DESC'),
));?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>