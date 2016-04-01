<?
// todo: перенести в init.php
if(isset($_POST["REGISTER"]['PASSWORD'])){
    $_REQUEST["REGISTER"]['CONFIRM_PASSWORD'] = $_POST["REGISTER"]['PASSWORD'];
}
if(isset($_POST["REGISTER"]['PASSWORD'])){
    $_REQUEST["REGISTER"]['CONFIRM_PASSWORD'] = $_POST["REGISTER"]['PASSWORD'];
}
if(isset($_POST["REGISTER"]['PASSWORD'])){
    $_REQUEST["REGISTER"]['LOGIN'] = $_POST["REGISTER"]['EMAIL'];
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Регистрация");
?><div class="inner">
    <h1>Зачем нужно регистрироваться?</h1>

    <p>
        Уважаемые партнеры, добрый день! В ближайшее время компания "Хогарт" будет рада представить круглосуточный сервис для оформления заказов и получения информации о ценах и остатках товара.
    </p>

    <div class="video-block">
        <div class="video-item fbig">
            <img src="/images/reg_video.jpg" alt=""/>
        </div>
    </div>

</div>
<?$APPLICATION->IncludeComponent(
    "bitrix:main.register",
    "register",
    Array(
        "COMPONENT_TEMPLATE" => ".default",
        "SHOW_FIELDS" => array("NAME","SECOND_NAME","LAST_NAME","PERSONAL_MOBILE","EMAIL","WORK_COMPANY", "PASSWORD","WORK_NOTES"),
        "REQUIRED_FIELDS" => array("NAME","SECOND_NAME","LAST_NAME"),
        "AUTH" => "N",
        "USE_BACKURL" => "Y",
        "SUCCESS_PAGE" => "?success",
        "SET_TITLE" => "Y",
        "USER_PROPERTY" => array(),
        "USER_PROPERTY_NAME" => ""
    )
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>