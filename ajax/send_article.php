<? require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); 

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	if (isset($_REQUEST['actionID']) && !empty($_REQUEST['actionID'])) {
		CModule::IncludeModule("iblock");

		$res = CIBlockElement::GetByID($_REQUEST['actionID']);
		if ($ar_res = $res->GetNext()) {
			$arEventFields = array(
			   	'ID'             => $ar_res['ID'],
				'NAME'           => $ar_res['NAME'],
				'EMAIL'          => $_REQUEST['email'],
				'DATE_FROM'      => FormatDate("d F", MakeTimeStamp($ar_res["ACTIVE_FROM"])),
				'DATE_TO'        => FormatDate("d F", MakeTimeStamp($ar_res["ACTIVE_TO"])),
				'PREVIEW_TEXT'   => $ar_res['PREVIEW_TEXT'],
				'DETAIL_PICTURE' => CFile::GetPath($ar_res['DETAIL_PICTURE']),
				'DETAIL_TEXT'    => $ar_res['DETAIL_TEXT'],
			);
			CEvent::Send("SEND_ACTION", 's1', $arEventFields);
		}
	}
	
	if (isset($_REQUEST['contactID']) && !empty($_REQUEST['contactID'])) {
		CModule::IncludeModule("iblock");

		$arFilter = array("ID" => $_REQUEST['contactID']);
		$res = CIBlockElement::GetList(array(), $arFilter, false, false);
		if ($ob = $res->GetNextElement()) {
			$arFields = $ob->GetFields();
			$arFields['PROPERTIES'] = $ob->GetProperties();
			
			$arEventFields = array(
				'ID'        => $arFields['ID'],
				'NAME'      => $arFields['NAME'],
				'ADRESS'    => $arFields['PROPERTIES']['adress']['VALUE'],
				'PHONE'     => implode(', ', $arFields['PROPERTIES']['phone']['VALUE']),
				'EMAIL'     => implode(', ', $arFields['PROPERTIES']['mail']['VALUE']),
				'EMAIL_TO'  => $_REQUEST['email'],
				'BY_CAR'    => $arFields['PROPERTIES']['by_car']['~VALUE']['TEXT'],
				'BY_PUBLIC' => $arFields['PROPERTIES']['by_public']['~VALUE']['TEXT'],
			);
			CEvent::Send("SEND_CONTACT", 's1', $arEventFields);
		}
	}
}