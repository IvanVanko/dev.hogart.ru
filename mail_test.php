<?php 
    ini_set( 'display_errors', 1 );
    error_reporting( E_ALL );
    $from = "sergey@akvilon-web.ru";
    $to = "murdoc3862@gmail.com, molchanov.ac@gmail.com";
    $subject = "PHP Mail Test script";
    $message = "This is a test to check the PHP Mail functionality";
    $headers = "From:" . $from;
    if (mail($to,$subject,$message, $headers)) {
    	echo "GOOD<br />";
    };
    echo "Test email sent";
?>