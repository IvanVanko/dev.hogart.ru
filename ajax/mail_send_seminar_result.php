<?
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>

<?


function check($var) {
	return (isset($var) && !empty($var));
}

if (check($_POST['name'])  && isset($_POST['adress']) && check($_POST['seminar_name']) && check($_POST['email']) && check($_POST['start_time'])  && check($_POST['org']))
{

$mail = array(
	'SEMINAR_USER_NAME' =>  $_POST['name'],
	'SEMINAR_ORG' =>  $_POST['org'],
	'SEMINAR_START' => $_POST['start_time'],
	'SEMINAR_ADRESS' => $_POST['adress'],
	'SEMINAR_USER_EMAIL' => $_POST['email'],
	'SEMINAR_NAME' => $_POST['seminar_name']
);

if ($event_sent_id = CEvent::Send(1210, 's1', $mail, "Y", 113)) {
	$arVALUE[166] = "SENDING";
	CModule::IncludeModule("form");
	CFormResult::SetField( $_POST['FIND_ID'], 'FILE_SEND', $arVALUE);
}	
if (check($_POST['html'])){
	
	$html =htmlspecialchars_decode(json_decode($_POST['html']));		
  $dompdf = new \Dompdf\Dompdf();
            $dompdf->load_html($html);
            $dompdf->set_paper('A4', 'portrait');
            $dompdf->render();
            $pdfPath = sys_get_temp_dir() . '/ticket-' . $arFormResult['SEMINAR_ID'] . '-' . uniqid() . '.pdf';
            file_put_contents($pdfPath, $dompdf->output());
			
 $arFile = \CFile::MakeFileArray($pdfPath);
            $arFile["name"] = "Пригласительный билет на " . $arResult['SEMINAR_NAME'] . ".pdf";
            $arFile["MODULE_ID"] = "main";
            $fid = \CFile::SaveFile($arFile, "main");
            $dataAttachment = array(
                'EVENT_ID' => $event_sent_id,
                'FILE_ID' => $fid,
            );
            \Bitrix\Main\Mail\Internal\EventAttachmentTable::add($dataAttachment);
			}
if (intval($event_sent_id)) {
        printf("<span class='msg-success'>Сообщение отправлено.</span>");
    } else {
        printf("<span class='msg-fail'>Сообщение не отправлено. Произошла ошибка.</span>");
    }
}
?>