<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Обучение");
?>
<div class="inner">
    <h2>Все отзывы</h2>
    <?$comments_cnt = $APPLICATION->IncludeComponent("kontora:element.list", "seminar_otziv", array(
        "IBLOCK_ID"     => 23,
        "PROPS"         => "Y",
        'FILTER'        => array('PROPERTY_seminar_id' => $_REQUEST['CID']),
    ));?>
<!--    <a class="all-comments" href="#">Все отзывы</a>-->

<!--    <h2>Оставить отзыв</h2>-->
    <? //$APPLICATION->IncludeComponent("kontora:comments.addform", "seminar-comment-form", array());?>
    <br/><br/>
    <hr/>
    <ul class="lear-base-bottom-href">
        <li><a href="/learn/" class="cal">календарь Семинаров</a></li>
        <li><a href="/learn/archive-seminarov/" class="base">Архив Семинаров</a></li>
    </ul>

</div>
<?$APPLICATION->IncludeComponent(
    "kontora:element.detail",
    "seminar-sidebar",
    Array(
        "ID" => $_REQUEST["CID"],
        "PROPS" => "Y",
        "PROPERTY_CODE" => Array("adress")
    )
);?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>