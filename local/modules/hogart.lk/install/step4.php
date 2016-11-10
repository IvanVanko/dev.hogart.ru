<?php

if (!check_bitrix_sessid()) return;

if (!empty($_GET["DADATA_SERVICE_URL"])) {
    COption::SetOptionString($MODULE_ID, "DADATA_SERVICE_URL", $_GET["DADATA_SERVICE_URL"]);
}

if (!empty($_GET["DADATA_API_KEY"])) {
    COption::SetOptionString($MODULE_ID, "DADATA_API_KEY", $_GET["DADATA_API_KEY"]);
}

CAdminMessage::ShowNote("Модуль ЛК установлен");
?>

<form action="<?echo $APPLICATION->GetCurPage()?>">
    <input type="hidden" name="lang" value="<?= LANG ?>">
    <input type="submit" name="" value="<?echo GetMessage("MOD_BACK")?>">
    <form>
