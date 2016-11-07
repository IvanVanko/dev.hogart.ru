<?
/**
 * @global $APPLICATION
 */
define("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

if (isset($_REQUEST["backurl"]) && strlen($_REQUEST["backurl"])>0) 
	LocalRedirect($backurl);

global $forgot_password, $change_password;
if ($change_password == "yes" || $forgot_password == "yes") {
	$APPLICATION->AuthForm([]);
} else {
	LocalRedirect("/account/");
}
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>