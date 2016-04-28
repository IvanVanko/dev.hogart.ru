<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Archive of seminars");
$APPLICATION->SetTitle("Archive of seminars");
?>
<div class="inner no-full">
    <?$APPLICATION->IncludeComponent("bitrix:news", "archives", array(
        'IBLOCK_ID' => 39,
        "FILTER_NAME"   => "filter",
//        "FILTER"   => "filter",
        'SEF_MODE' => 'Y',
        "SEF_FOLDER" => "/en/learn/archive-seminarov/",
        "SEF_URL_TEMPLATES" => Array(
            "detail" => "#ELEMENT_CODE#/",
            "news" => "/en/learn/archive-seminarov/",
        ),
        'ORDER' => array('PROPERTY_sem_start_date' => 'DESC'),

    ));?>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>