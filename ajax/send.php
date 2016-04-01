<?

$email = trim(stripslashes($_POST['sending_email']));
$page_title = trim(stripslashes($_POST['title_name']));
$page_href = trim(stripslashes($_POST['page_href']));

//$message = trim(stripslashes($_POST['message']));
//$message = 'Хочу быть вашим партнером!!!';
$message = 'Вы поделились ссылкой';

//$email_from = 'udalenwik@yandex.ru';
$email_from = 'noreply@hogart.ru';
//$email_to = 'udalenwik@yandex.ru';
$email_to = $email;

$body = 'Добрый день.' . "\n\n" .
//        'Email: ' . $email . "\n\n" .
        'Вы поделились страницей "' . $page_title . '"' . "\n\n" .
        'С ее содержимым можно ознакомиться по <a href="' . $page_href . '">ссылке</a>' . "\n\n";
//        'Сообщение: ' . $message;

$subject = substr($message, 0, 40);

$success = mail($email_to, $subject, $body, 'From: <' . $email_from . '>');
//Добрый день.
//
//Вы поделились страницей title
//С ее содежимым можно ознакомиться по ссылке link
/*echo '<p>Email Sent!</p>';*/
echo $success;

/*if ($success == 1) {
    echo $body;

} else {
    echo '0';
}*/

?>
