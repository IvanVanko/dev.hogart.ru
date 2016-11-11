<?
$prevStepKeys =array_filter(array_keys($_GET), function ($key) { return preg_match("%^RABBITMQ_%", $key); });
foreach ($prevStepKeys as $key) {
    COption::SetOptionString($MODULE_ID, $key, $_GET[$key]); 
}
?>
<form action="<?echo $APPLICATION->GetCurPage()?>" name="form1">
    <?=bitrix_sessid_post()?>
    <input type="hidden" name="lang" value="<?= LANGUAGE_ID ?>">
    <input type="hidden" name="id" value="<?= $MODULE_ID ?>">
    <input type="hidden" name="install" value="Y">
    <input type="hidden" name="step" value="3">

    <table cellpadding="3" cellspacing="0" border="0" width="0%">
        <tr>
            <td><p><label for="soap_scheme">Схема SOAP-сервиса</label></p></td>
            <td><input type="text" name="SOAP_SERVICE_SCHEME" id="soap_scheme" value="<?= COption::GetOptionString($MODULE_ID, "SOAP_SERVICE_SCHEME")?>"></td>
        </tr>
        <tr>
            <td><p><label for="soap_host">Хост SOAP-сервиса</label></p></td>
            <td><input type="text" name="SOAP_SERVICE_HOST" id="soap_host" value="<?= COption::GetOptionString($MODULE_ID, "SOAP_SERVICE_HOST")?>"></td>
        </tr>
        <tr>
            <td><p><label for="soap_port">Порт SOAP-сервиса</label></p></td>
            <td><input type="text" name="SOAP_SERVICE_PORT" id="soap_port" value="<?= COption::GetOptionString($MODULE_ID, "SOAP_SERVICE_PORT")?>"></td>
        </tr>
        <tr>
            <td><p><label for="soap_endpoint">Endpoint SOAP-сервиса</label></p></td>
            <td><input type="text" name="SOAP_SERVICE_ENDPOINT" id="soap_endpoint" value="<?= COption::GetOptionString($MODULE_ID, "SOAP_SERVICE_ENDPOINT")?>"></td>
        </tr>
        <tr>
            <td><p><label for="soap_login">Логин SOAP-сервиса</label></p></td>
            <td><input type="text" name="SOAP_SERVICE_LOGIN" id="soap_login" value="<?= COption::GetOptionString($MODULE_ID, "SOAP_SERVICE_LOGIN")?>"></td>
        </tr>
        <tr>
            <td><p><label for="soap_password">Пароль SOAP-сервиса</label></p></td>
            <td><input type="text" name="SOAP_SERVICE_PASSWORD" id="soap_password" value="<?= COption::GetOptionString($MODULE_ID, "SOAP_SERVICE_PASSWORD")?>"></td>
        </tr>
    </table>
    <br>
    <input type="submit" name="step_install" value="Продолжить">
</form>