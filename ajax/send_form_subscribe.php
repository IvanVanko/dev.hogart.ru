<?    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

	if (isset($_POST['EMAIL']) &&(isset($_POST['entity']['UF_SUBSCRIBER_PHONE']))) {
		file_put_contents($_SERVER['DOCUMENT_ROOT']."/log55.txt",var_export($_POST,true), FILE_APPEND);
		$result['error'] = [];
		$arrRub = $_POST['RUB_ID'];

		if (empty($_POST['EMAIL']))
			$result['error'][] = "Введите E-mail";
		elseif (!preg_match("/.+@.+\..+/i", $_POST['EMAIL'])) 
			$result['error'][] = "Введите корректный E-mail";
			
		if (empty($arrRub) && empty($_POST['subscribe-news-more']))
			$result['error'][] = "Выберите хотя бы одну сферу";
		
		if (empty($_POST['entity']['UF_SUBSCRIBER_PHONE']))
			$result['error'][] = "Введите телефон";
		elseif (strlen($_POST['entity']['UF_SUBSCRIBER_PHONE']) != 17) 
			$result['error'][] = "Введите корректный телефон";
		
		if (!empty($_POST['subscribe-news-more']) && (empty($_POST['other'])))
			$result['error'][] = "Введите прочее";
		
		
		
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
		if (empty($result['error']) && $RESULT_ID = CFormResult::Add($FORM_ID, $arValues)) {
			$result['success'] = "Сообщение отправлено";
		} 
		echo json_encode($result);
	}
}