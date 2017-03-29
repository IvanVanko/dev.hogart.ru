<?    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

	if (isset($_POST['EMAIL']) &&(isset($_POST['entity']['UF_SUBSCRIBER_PHONE']))) {
		$error['error'] = [];
		$result['error'] = [];
		$arrRub = $_POST['RUB_ID'];

		if (empty($_POST['EMAIL']))
			$error['error'][] = "Введите E-mail";
		elseif (!preg_match("/.+@.+\..+/i", $_POST['EMAIL'])) 
			$error['error'][] = "Введите корректный E-mail";
			
	//	if (empty($arrRub) && empty($_POST['subscribe-news-more']))
	//		$error['error'][] = "Выберите хотя бы одну сферу";
		
		if (empty($_POST['entity']['UF_SUBSCRIBER_PHONE']))
			$error['error'][] = "Введите телефон";
		elseif (strlen($_POST['entity']['UF_SUBSCRIBER_PHONE']) != 17) 
			$error['error'][] = "Введите корректный телефон";
		
		if (!empty($_POST['subscribe-news-more']) && (empty($_POST['other'])))
			$error['error'][] = "Введите прочее";
		
		
		
		$other = (!empty($_POST['subscribe-news-more'])) ? $_POST['other'] : "";
		$arValues = [
			"form_text_174" => $_POST['EMAIL'],
			"form_text_168" => $_POST['entity']['UF_SUBSCRIBER_PHONE'],
			"form_text_169" => $_POST['subscribe-news-name'],
			"form_text_170" => $_POST['subscribe-news-subname'],
			"form_text_171" => $_POST['meneger_yes'],
			"form_text_172" => implode(", ", $arrRub),
			"form_text_173" => $other
		];
		$FORM_ID = 20;
		CModule::IncludeModule("form");
	 
		//если результат добавился в веб форму, передаем ID и поля
		if (empty($error['error']) && $RESULT_ID = CFormResult::Add($FORM_ID, $arValues)) {
			$result['success'] = "Сообщение отправлено";
		} 
		echo json_encode($result);
	}
}