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
?>
<div class="row">
    <div class="col-md-<?= (!$USER->IsAuthorized() ? "9" : "12") ?>">
        <h3><?= HogartHelpers::ShowStaticContent("register", "NAME") ?></h3>
        <?= HogartHelpers::ShowStaticContent("register", "PREVIEW_TEXT") ?>
    </div>
    <? if (!$USER->IsAuthorized()): ?>
    <div class="col-md-3 aside">
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
    </div>
    <? endif; ?>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>