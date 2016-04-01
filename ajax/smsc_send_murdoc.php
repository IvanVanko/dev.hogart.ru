<?php
    
include_once "smsc_api.php";
$page_title = trim(stripslashes('Имя титле'));
$page_href = trim(stripslashes('http://www.hogart.ru/contacts/35/'));
$phone = trim(stripslashes('+79176213866'));

$message = 'Добрый день.' . "\n\n" .
    'Вы поделились страницей "' . $page_title . '"' . "\n\n" .
    'С ее содержимым можно ознакомиться по <a href="' . $page_href . '">ссылке</a>' . "\n\n";

$r = send_sms($phone, $message);

var_dump($r);

$success = ($r[1] > 0)?1:0;

echo $success;
