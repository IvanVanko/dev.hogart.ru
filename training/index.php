<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Обучение");
?>

<div class="inner">
    <div class="head-learn">
        <div class="col1">
            <h1>Обучение</h1>
            <h2>Семинары с назначенной датой</h2>
            <ul class="learn-head-link">
                <li><a href="#" class="icon-edit">Запись на семинары с открытой датой</a></li>
                <li><a href="#" class="icon-base">Архив Семинаров</a></li>
                <li><a href="#" class="border-bottom">Предложить тему семинара</a></li>
            </ul>
        </div>
        <div class="col2">
            <ul class="var-view">
                <li><a href="#" class="icon-cal active">На календаре</a></li>
                <li><a href="#" class="icon-list">Списком</a></li>
            </ul>
        </div>
    </div>
</div>
<!--<div class="dates-list">-->

    <?
/*$APPLICATION->IncludeComponent("bitrix:news.list", "seminars", array(
        'IBLOCK_ID' => '8',
        "IBLOCK_TYPE" => "training",
        "PROPERTY_CODE" => Array("adress")
    ));*/
?>
<!--</div>-->
    <ul class="js-dateArray" id="calendar-array">
        <li data-date="05/21/2015">Hogart-редизайн сайта</li>
        <li data-date="05/11/2015">Hogart-редизайн сайта</li>
        <li data-date="05/19/2015">Семинар на тему: «Особенности монтажа,
            настройки и обслуживания водогрейных
            котлов De Dietrich»
        </li>
        <li data-date="05/18/2015">Семинар на тему: «test»
        </li>
    </ul>
<div class="calendar-cnt" id="learn-calendar" data-datepicker="#calendar-array"></div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>