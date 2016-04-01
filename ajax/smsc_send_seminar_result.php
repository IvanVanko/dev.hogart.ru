<?
if((!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) &&
        (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest')) die();
    else if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
        require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if ($_POST["sending_phone"]!='') {
    include_once "smsc_api.php";
    $page_href = trim(stripslashes($_POST['page_href']));
    $phone = preg_replace("/( |\(|\))/","",$_POST["sending_phone"]);
    $seminar_name = trim(stripslashes($_POST['seminar_name']));
//    $phone = str_replace('+-()', '', $phone);
//    echo $phone."\n\n";
    $message = 'Добрый день.' . "\n\n" .
        'Вы зарегистрировались на семинар "' . $seminar_name . '"' . "\n\n" .
        'Пропуск на семинар можно просмотреть на странице ' . $page_href . "\n\n".
        'www.hogart.ru'."\n" ;



    fileDump(array($phone, $message), true);
    $r = send_sms($phone, $message);
//        $_POST["translit"], $_POST["time"], 0,
//        $_POST["flash"], $_POST["sender"]);

    // $r = array(<id>, <количество sms>, <стоимость>, <баланс>) или array(<id>, -<код ошибки>)

    if ($r[1] > 0){
        $success=1;
        //echo $success;
        echo "Сообщение отправлено";
    }
    else{
        $success=0;
        //echo $success;
        echo "Произошла ошибка № ", -$r[1];
//        echo "Сообщение отправлено";
    }
}