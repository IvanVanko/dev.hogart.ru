<?
if ($_POST["sending_phone"]!='') {
    include_once "smsc_api.php";
    $page_title = trim(stripslashes($_POST['title_name']));
    $page_href = trim(stripslashes($_POST['page_href']));
    $phone = trim(stripslashes($_POST['sending_phone']));
//    $phone = str_replace('+-()', '', $phone);
//    echo $phone."\n\n";
    $message = 'Добрый день.' . "\n\n" .
        'Вы поделились страницей "' . $page_title . '"' . "\n\n" .
        'С ее содержимым можно ознакомиться по <a href="' . $page_href . '">ссылке</a>' . "\n\n";

    $r = send_sms($phone, $message);
//        $_POST["translit"], $_POST["time"], 0,
//        $_POST["flash"], $_POST["sender"]);

    // $r = array(<id>, <количество sms>, <стоимость>, <баланс>) или array(<id>, -<код ошибки>) 

    if ($r[1] > 0){
        $success=1;
        echo $success;
//        echo "Сообщение отправлено";
    }
    else{
        $success=0;
        echo $success;
//        echo "Произошла ошибка № ", -$r[1];
//        echo "Сообщение отправлено";
    }

}

?>