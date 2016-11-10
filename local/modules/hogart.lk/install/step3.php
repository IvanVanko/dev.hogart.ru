<?php
$prevStepKeys =array_filter(array_keys($_GET), function ($key) { return preg_match("%^SOAP_SERVICE_%", $key); });
foreach ($prevStepKeys as $key) {
    COption::SetOptionString($MODULE_ID, $key, $_GET[$key]);
}
?>

<form action="<?echo $APPLICATION->GetCurPage()?>" name="form1">
    <?=bitrix_sessid_post()?>
    <input type="hidden" name="lang" value="<?= LANGUAGE_ID ?>">
    <input type="hidden" name="id" value="<?= $MODULE_ID ?>">
    <input type="hidden" name="install" value="Y">
    <input type="hidden" name="step" value="4">

    <table cellpadding="3" cellspacing="0" border="0" width="0%">
        <tr>
            <td><p><label for="dadata_url">URL Service DaData</label></p></td>
            <td><input type="text" name="DADATA_SERVICE_URL" id="dadata_url" value="<?= COption::GetOptionString($MODULE_ID, "DADATA_SERVICE_URL")?>"></td>
        </tr>
        <tr>
            <td><p><label for="dadata_api">API Key DaData</label></p></td>
            <td><input type="text" name="DADATA_API_KEY" id="dadata_api" value="<?= COption::GetOptionString($MODULE_ID, "DADATA_API_KEY")?>"></td>
        </tr>
    </table>
    <br>
    <input type="submit" name="step_install" value="Завершить установку">
</form>

