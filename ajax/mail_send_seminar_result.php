<?if((!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) &&
        (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest')) die();
    else if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
        require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>

<?
if (!empty($_REQUEST['seminar_name']) && !empty($_REQUEST['page_href']) && !empty($_REQUEST['email'])) {
    $event_sent_id = CEvent::Send("SEND_SEMINAR_RESULT",SITE_ID,array("USER_EMAIL" => $_REQUEST['email'], "RESULT_URL" => $_REQUEST["page_href"], "SEMINAR_NAME" => $_REQUEST['seminar_name']));
    if (intval($event_sent_id)) {
        echo "Сообщение отправлено";
    } else {
        echo "Произошла ошибка";
    }
}
?>