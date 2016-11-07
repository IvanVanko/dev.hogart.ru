<form action="<?echo $APPLICATION->GetCurPage()?>" name="form1">
    <?=bitrix_sessid_post()?>
    <input type="hidden" name="lang" value="<?= LANGUAGE_ID ?>">
    <input type="hidden" name="id" value="<?= $MODULE_ID ?>">
    <input type="hidden" name="install" value="Y">
    <input type="hidden" name="step" value="2">

    <table cellpadding="3" cellspacing="0" border="0" width="0%">
        <tr>
            <td><p><label for="rabbitmq_host">Хост сервера RabbitMQ</label></p></td>
            <td><input type="text" name="RABBITMQ_HOST" id="rabbitmq_host" value="<?= COption::GetOptionString($MODULE_ID, "RABBITMQ_HOST")?>"></td>
        </tr>
        <tr>
            <td><p><label for="rabbitmq_port">Порт сервера RabbitMQ</label></p></td>
            <td><input type="text" name="RABBITMQ_PORT" id="rabbitmq_port" value="<?= COption::GetOptionString($MODULE_ID, "RABBITMQ_PORT")?>"></td>
        </tr>
        <tr>
            <td><p><label for="rabbitmq_vhost">Виртуальный хост сервера RabbitMQ</label></p></td>
            <td><input type="text" name="RABBITMQ_VHOST" id="rabbitmq_vhost" value="<?= COption::GetOptionString($MODULE_ID, "RABBITMQ_VHOST")?>"></td>
        </tr>
        <tr>
            <td><p><label for="rabbitmq_login">Логин сервера RabbitMQ</label></p></td>
            <td><input type="text" name="RABBITMQ_LOGIN" id="rabbitmq_login" value="<?= COption::GetOptionString($MODULE_ID, "RABBITMQ_LOGIN")?>"></td>
        </tr>
        <tr>
            <td><p><label for="rabbitmq_password">Пароль сервера RabbitMQ</label></p></td>
            <td><input type="text" name="RABBITMQ_PASSWORD" id="rabbitmq_password" value="<?= COption::GetOptionString($MODULE_ID, "RABBITMQ_PASSWORD")?>"></td>
        </tr>
    </table>
    <br>
    <input type="submit" name="step_install" value="Продолжить">
</form>