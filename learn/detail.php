<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Обучение");
?>
<?
$APPLICATION->SetPageProperty("body_class","reg_page");
/*$results=$_GET["RESULT_ID"];
if ($results) {?>
<div class="inner">

<?$APPLICATION->IncludeComponent("pirogov:custom.form.result.list", "", Array(
        "SEF_MODE" => "Y",
        "WEB_FORM_ID" => 5,
    "FILTER" => array("ID"=>explode(",",$results)),
    )
);?>

</div>*/?>
<?/*} else {*/?>
    <script>

        $(document).ready(function () {
            $('.empty-btn.black.to-otziv').click(function () {
                var hrefData = $(this).attr('href'),
                otzivTop = $(hrefData).offset().top;
//                console.log($(this).attr('href'));
                $('html, body').animate({
                    scrollTop:otzivTop-$('.header-cnt').height()*2
                }, 1000);
                return false;
            });
        });

    </script>
<div class="inner">
<?
//$APPLICATION->IncludeComponent(
//	"kontora:element.detail",
//	"seminar",
//	Array(
//		"ID" => $_REQUEST["CID"],
//		"PROPS" => "Y",
//		"PROPERTY_CODE" => Array("adress")
//	)
//);
    ?>
<?
    $APPLICATION->IncludeComponent(
        "bitrix:news.detail",
        "hogart_seminar_detail",
        Array(
            "DISPLAY_DATE" => "Y",
            "DISPLAY_NAME" => "Y",
            "DISPLAY_PICTURE" => "Y",
            "DISPLAY_PREVIEW_TEXT" => "Y",
            "ADD_SECTIONS_CHAIN" => "Y",
            "ADD_ELEMENT_CHAIN" => "Y",
            "IBLOCK_TYPE" => "training",
            "IBLOCK_ID" => "8",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "0",
            "CACHE_FILTER" => "N",
            "CACHE_GROUPS" => "N",
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
global $seminarTitle;
if($seminarTitle)
    $APPLICATION->AddChainItem($seminarTitle);
?>
<?/*}*/?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>