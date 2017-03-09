<?
if (isset($_POST["sending_phone"]) && !empty($_POST["sending_phone"])) {
	include_once "smsc_api.php";
    $page_href = trim(stripslashes($_POST['page_href']));
    $phone = preg_replace("/( |\(|\))/","",$_POST["sending_phone"]);
    $seminar_name = trim(stripslashes($_POST['seminar_name']));
//    $phone = str_replace('+-()', '', $phone);
//    echo $phone."\n\n";
    $message = 'Добрый день.' . "\n\n" .
        'Вы зарегистрировались на семинар: "' . $seminar_name . '"' . "\n\n" .
        'Пропуск на семинар можно просмотреть на странице: ' . $page_href . "\n\n".
        'www.hogart.ru'."\n" ;

    $r = send_sms($phone, $message);
//        $_POST["translit"], $_POST["time"], 0,
//        $_POST["flash"], $_POST["sender"]);

    // $r = array(<id>, <количество sms>, <стоимость>, <баланс>) или array(<id>, -<код ошибки>)

    if ($r[1] > 0){
        $success=1;
        printf("<span class='msg-success'>Сообщение отправлено.</span>");
    }
    else{
        printf("<span class='msg-fail'>Сообщение не отправлено. Произошла ошибка '%s'.</span>", $r[1]);
    }
}