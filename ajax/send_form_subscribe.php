<?    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
if (isset($_POST['EMAIL']) &&(isset($_POST['entity']['UF_SUBSCRIBER_PHONE'])))
{
$arrRub = array_pop($_POST['RUB_ID']);
$arrRub = $_POST['RUB_ID'];

	$arValues = array (
		"form_text_167" => $_POST['EMAIL'],
		"form_text_168" => $_POST['entity']['UF_SUBSCRIBER_PHONE'],
		"form_text_169" => $_POST['subscribe-news-name'],
		"form_text_170" => $_POST['subscribe-news-subname'],
		"form_text_171" => $_POST['meneger_yes'],
		"form_text_172" => implode(", ", $arrRub),
		"form_text_173" => $_POST['other']
	);
	$FORM_ID = 20;
	CModule::IncludeModule("form");
 
	//если результат добавился в веб форму, передаем ID и поля
	if ($RESULT_ID = CFormResult::Add($FORM_ID, $arValues)) {
		echo "Сообщение отправлено";
	}
	
	
}
