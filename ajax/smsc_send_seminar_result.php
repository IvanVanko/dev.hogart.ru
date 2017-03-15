<?
//file_put_contents($_SERVER['DOCUMENT_ROOT']."/log111.txt",var_export($_POST,true), FILE_APPEND);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if (isset($_POST["sending_phone"]) && !empty($_POST["sending_phone"])) {
    include_once "smsc_api.php";

    $page_href = trim(stripslashes($_POST['page_href']));
    $phone = preg_replace("/( |\(|\))/", "", $_POST["sending_phone"]);
    $seminar_name = trim(stripslashes($_POST['seminar_name']));
    $start_time = trim(stripslashes($_POST['start_time']));
    $org = trim(stripslashes($_POST['org']));
    $code = trim(stripslashes($_POST['code']));
    $adress = trim(stripslashes($_POST['adress']));


    $message = 'Регистрация подтверждена!' . $seminar_name . " " . $start_time . "\n\n" .
        'Адрес "' . $adress . '"' . "\n\n" .
        'Ваш код участника ' . $code . "\n\n" .
        'Организатор:' . $org . "\n";
    $r = send_sms($phone, $message);
//        $_POST["translit"], $_POST["time"], 0,
//        $_POST["flash"], $_POST["sender"]);

    // $r = array(<id>, <количество sms>, <стоимость>, <баланс>) или array(<id>, -<код ошибки>)

    if ($r[1] > 0) {
        $success = 1;
        printf("<span class='msg-success'>Сообщение отправлено.</span>");
    } else {
        printf("<span class='msg-fail'>Сообщение не отправлено. Произошла ошибка '%s'.</span>", $r[1]);
    }
}