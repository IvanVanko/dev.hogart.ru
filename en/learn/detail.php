<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Learn");
?>
<?
$APPLICATION->SetPageProperty("body_class","reg_page");
?>
<script>
    $(document).ready(function () {
        $('.empty-btn.black.to-otziv').click(function () {
            var hrefData = $(this).attr('href'),
            otzivTop = $(hrefData).offset().top;
            $('html, body').animate({
                scrollTop:otzivTop-$('.header-cnt').height()*2
            }, 1000);
            return false;
        });
    });

</script>
<div class="inner">
<?
    $APPLICATION->IncludeComponent(
        "bitrix:news.detail",
        "hogart_seminar_detail",
        Array(
            "DISPLAY_DATE" => "Y",
            "DISPLAY_NAME" => "Y",
            "DISPLAY_PICTURE" => "Y",
            "DISPLAY_PREVIEW_TEXT" => "Y",
            "IBLOCK_TYPE" => "training",
            "IBLOCK_ID" => "39",
            //result_modifier.php
            'ORDER' => array('PROPERTY_sem_start_date' => 'DESC'),
            "SEF_FOLDER" => $arParams['SEF_FOLDER'],
            "ELEMENT_CODE" => $_REQUEST['ELEMENT_CODE'],
            "PROPERTY_CODE" => array(
                "sem_start_date"
            )
        ),
        $component
    );
?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>