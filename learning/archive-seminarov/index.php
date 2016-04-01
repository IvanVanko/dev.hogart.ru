<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Архив семинаров");
$APPLICATION->SetTitle("Архив семинаров");
?>
    <?$APPLICATION->IncludeComponent("bitrix:news", "archives", array(
        'IBLOCK_ID' => '8',
        "FILTER_NAME"   => "filter",
//        "FILTER"   => "filter",
        'SEF_MODE' => 'Y',
        "SEF_FOLDER" => "/learning/archive-seminarov/",
        "SEF_URL_TEMPLATES" => Array(
               "news" => "/learning/archive-seminarov/",
               "detail" => "#ELEMENT_CODE#/",
        ),
        'ORDER' => array('PROPERTY_sem_start_date' => 'DESC'),

    ));?>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>