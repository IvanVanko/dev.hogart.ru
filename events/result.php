<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$APPLICATION->ShowHead();
?>
<?$APPLICATION->IncludeComponent("pirogov:eventRegistrationResult", "", array(
    "ID" => $_GET['id']
))?>