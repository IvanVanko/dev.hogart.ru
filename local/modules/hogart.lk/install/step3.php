<?php
$prevStepKeys =array_filter(array_keys($_GET), function ($key) { return preg_match("%^SOAP_SERVICE_%", $key); });
foreach ($prevStepKeys as $key) {
    COption::SetOptionString($MODULE_ID, $key, $_GET[$key]);
}
if (!check_bitrix_sessid()) return;
CAdminMessage::ShowNote("Модуль ЛК установлен");
?>

<form action="<?echo $APPLICATION->GetCurPage()?>">
    <input type="hidden" name="lang" value="<?= LANG ?>">
    <input type="submit" name="" value="<?echo GetMessage("MOD_BACK")?>">
<form>
