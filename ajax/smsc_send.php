<?
if ($_POST["sending_phone"]!='') {
    include_once "smsc_api.php";
    $page_title = trim(stripslashes($_POST['title_name']));
    $page_href = trim(stripslashes($_POST['page_href']));
    $phone = trim(stripslashes($_POST['sending_phone']));
    $message_title = trim(stripslashes($_POST['message_title']));
    $user_msg = substr(trim(stripslashes($_POST['user_msg'])),0,25);

    if($message_title == '' && strpos($page_href, '/integrated-solutions/') !== false){
        if(preg_match('%^(.*?)/integrated-solutions/(.*?)/$%i', $page_href)){
            $message_title = 'Опыт решения в инженерии: ';
        }elseif(preg_match('%^(.*?)/integrated-solutions/(.*?)/(.*?)/$%i', $page_href)){
            $message_title = 'Обращаем ваше внимание на референс-объект: ';
        }
    }

    $message = "Здравствуйте!\n\n".
        ($message_title ? $message_title."\n" : '').
        $page_title."\n".
        "Подробнее на странице {$page_href}".
        ($user_msg ? "\n".$user_msg : '');

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