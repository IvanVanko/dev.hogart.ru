<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?
jsDump(mb_internal_encoding());
$arSeminarsIds = array();

function exec_script($url, $post_data)
	{
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $url);

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			// указываем, что у нас POST запрос
			curl_setopt($ch, CURLOPT_POST, 1);
			// добавляем переменные
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

			$output = curl_exec($ch);
//file_put_contents($_SERVER['DOCUMENT_ROOT']."/log1211.txt",var_export($output,true), FILE_APPEND);
			curl_close($ch);


	}
foreach ($arResult['arrResults'] as &$arrResult) {
    $result_id = $arrResult['ID'];
    $seminar_user_name = "";
    $seminar_user_mid_name = "";
    $seminar_user_last_name = "";
    foreach ($arResult['arrAnswers'][$result_id] as $arQuestion) {
        foreach ($arQuestion as $key => $arAnswer) {
            if ($arAnswer['SID'] == 'SEMINAR_ID') {
                $seminar_id = $arAnswer['USER_TEXT'];
                $arSeminarsIds[] = $seminar_id;
                $arrResult['SEMINAR_ID'] = $seminar_id;
                break;
            } else if ($arAnswer['SID'] == 'SEMINAR_USER_NAME') {
                $seminar_user_name = $arAnswer['USER_TEXT'];
            } else if ($arAnswer['SID'] == 'SEMINAR_USER_LNAME') {
                $seminar_user_last_name = $arAnswer['USER_TEXT'];
            } else if ($arAnswer['SID'] == 'SEMINAR_USER_MNAME') {
                $seminar_user_mid_name = $arAnswer['USER_TEXT'];
            } else if ($arAnswer['SID'] == 'SEMINAR_USER_PHONE') {
                $sending_phone = html_entity_decode($arAnswer['USER_TEXT']);
                $seminar_user_phone = $arAnswer['USER_TEXT'];
                $arrResult['SEND_PHONE'] = $sending_phone;
            } else if ($arAnswer['SID'] == 'SEMINAR_NAME') {
                $arrResult['SEMINAR_NAME'] = $arAnswer['USER_TEXT'];
            } else if ($arAnswer['SID'] == 'SEMINAR_USER_CMP') {
                $arrResult['USER_COMPANY'] = $arAnswer['USER_TEXT'];
            } else if ($arAnswer['SID'] == 'SEMINAR_EAN_CODE') {
                if (!isset($arrResult['BARCODE_BASE64']) && strlen($arAnswer['USER_TEXT']) === 13) {
			
                    $arrResult['BARCODE'] = $arAnswer['USER_TEXT'];
                    $arrResult['BARCODE_BASE64'] = HogartHelpers::generateBarCodeImageData(substr($arAnswer['USER_TEXT'], 0, 12));
                }
            }
        }
    }
    $arrResult['USER_NAME'] = $seminar_user_name . " " . $seminar_user_mid_name . " " . $seminar_user_last_name;
}


$elements = BXHelper::getElements(
    array(),
    array('IBLOCK_ID' => SEMINAR_IBLOCK_ID, 'ID' => $arSeminarsIds),
    false,
    false,
    array(
        'ID',
        'NAME',
        'PROPERTY_lecturer',
        'PROPERTY_lecturer.PROPERTY_status',
        'PROPERTY_lecturer.PROPERTY_company',
        'PROPERTY_lecturer.NAME',
        'PROPERTY_brand.NAME',
        'PROPERTY_org.NAME',
        'PROPERTY_org.PROPERTY_phone',
        'PROPERTY_org.PROPERTY_mail',
        'PROPERTY_sem_start_date',
		'PROPERTY_sem_end_date',
        'PROPERTY_time',
        'PROPERTY_end_time',
        'PROPERTY_address'
    ), true, 'ID');

$elements = $elements['RESULT'];

foreach ($elements as &$arElement) {

    if (!is_assoc($arElement)) {
        //      $seminar_name = $arElement['NAME'];
        $arAddToElement = array();
        foreach ($arElement as $arElementVariant) {

            $arAddToElement['BRANDS'][] = $arElementVariant['PROPERTY_BRAND_NAME'];
            $arAddToElement['ORG_NAME'] = $arElementVariant['PROPERTY_ORG_NAME'];
            $arAddToElement['ORG_MAIL'] = $arElementVariant['PROPERTY_ORG_PROPERTY_MAIL_VALUE'];
            $arAddToElement['ORG_PHONE'] = $arElementVariant['PROPERTY_ORG_PROPERTY_PHONE_VALUE'];

            $arAddToElement['ADDRESS'] = $arElementVariant['PROPERTY_ADDRESS_VALUE'];
            if (!isset($arAddToElement['DISPLAY_BEGIN_DATE'])) {
				$end_time_value = "";

		if (!empty($arElementVariant['PROPERTY_SEM_START_DATE_VALUE']) && !empty($arElementVariant['PROPERTY_SEM_END_DATE_VALUE']))
					$end_time_value = ($arElementVariant['PROPERTY_SEM_START_DATE_VALUE'] < $arElementVariant['PROPERTY_SEM_END_DATE_VALUE']) ? " - " . 
				 FormatDate("d F Yг. ", MakeTimeStamp($arElementVariant["PROPERTY_SEM_END_DATE_VALUE"])) : "";
                $arAddToElement['DISPLAY_BEGIN_DATE'] = FormatDate("d F Yг. ", MakeTimeStamp($arElementVariant["PROPERTY_SEM_START_DATE_VALUE"])) .$end_time_value ." / Время начала " . 
				$arElementVariant["PROPERTY_TIME_VALUE"];
		}
            $lecturer_id = $arElementVariant['PROPERTY_LECTURER_VALUE'];
            if (!isset($arAddToElement['LECTURERS'][$lecturer_id])) {
                $arAddToElement['LECTURERS'][$lecturer_id] = array(
                    'NAME' => $arElementVariant['PROPERTY_LECTURER_NAME'],
                    'POST' => $arElementVariant['PROPERTY_LECTURER_PROPERTY_STATUS_VALUE'],
                    'COMPANY' => $arElementVariant['PROPERTY_LECTURER_PROPERTY_COMPANY_VALUE'],
                );
            }
        }
        $arAddToElement['BRANDS'] = array_unique($arAddToElement['BRANDS']);
        $arElement = $arElement + $arAddToElement;
        $seminar_name = $arElementVariant['NAME'];
        $adress = $arAddToElement['ADDRESS'];
        $start_time = $arAddToElement['DISPLAY_BEGIN_DATE'];
        $org = $arAddToElement['ORG_NAME'] . " " . $arAddToElement['ORG_MAIL'] . " " . $arAddToElement['ORG_PHONE'];
    } else {
        $seminar_name = $arElement['NAME'];
        $arElement['ORG_NAME'] = $arElement['PROPERTY_ORG_NAME'];
        $arElement['ORG_MAIL'] = $arElement['PROPERTY_ORG_PROPERTY_MAIL_VALUE'];
        $arElement['ORG_PHONE'] = $arElement['PROPERTY_ORG_PROPERTY_PHONE_VALUE'];
        $arElement['ADDRESS'] = $arElement['PROPERTY_ADDRESS_VALUE'];
        //	var_dump($arElement["PROPERTY_SEM_START_DATE_VALUE"]);
        //die;
	    if (!isset($arElement['DISPLAY_BEGIN_DATE'])) {
				$end_time_value = "";

				if (!empty($arElement['PROPERTY_SEM_START_DATE_VALUE']) && !empty($arElement['PROPERTY_SEM_END_DATE_VALUE']))
					$end_time_value = ($arElement['PROPERTY_SEM_START_DATE_VALUE'] < $arElement['PROPERTY_SEM_END_DATE_VALUE']) ? " - " . 
				 FormatDate("d F Yг. ", MakeTimeStamp($arElement["PROPERTY_SEM_END_DATE_VALUE"])) : "";
                $arElement['DISPLAY_BEGIN_DATE'] = FormatDate("d F Yг. ", MakeTimeStamp($arElement["PROPERTY_SEM_START_DATE_VALUE"])) .$end_time_value ." / Время начала " . 
				$arElement["PROPERTY_TIME_VALUE"];
            }
        $arElement['BRANDS'] = array('PROPERTY_BRAND_NAME');
        $lecturer_id = $arElement['PROPERTY_LECTURER_VALUE'];
        $arElement['LECTURERS'][$lecturer_id] = array(
            'NAME' => $arElement['PROPERTY_LECTURER_NAME'],
            'POST' => $arElement['PROPERTY_LECTURER_PROPERTY_STATUS_VALUE'],
            'COMPANY' => $arElement['PROPERTY_LECTURER_PROPERTY_COMPANY_VALUE'],
        );

    }
}


$arResult['SEMINARS'] = $elements;
$arResult['SEMINAR_NAME'] = $seminar_name;
//$APPLICATION->RestartBuffer();


if ($arResult['FILE_SEND'] == '') {

//  require_once $_SERVER['DOCUMENT_ROOT'] . "/local/include/lib/mPDF/Mpdf.php";


//    $TEXT = "<h1 style='color:#000;'>Hello World</h1>";
//    $mpdf = new Mpdf\Mpdf();
//    $mpdf->WriteHTML($TEXT);
//    $mpdf->Output();
//
//    die;


    if (!empty($arResult['arrResults'])) {

        foreach ($arResult['arrResults'] as $arFormResult) {
            $s_id = $arFormResult['SEMINAR_ID'];
            $url = 'http://' . $_SERVER['SERVER_NAME'] . '/ajax/smsc_send_seminar_result.php';
            $params = array(
                'seminar_name' => $arResult['SEMINAR_NAME'],
                'org' => $arResult['SEMINARS'][$s_id]['ORG_NAME'] . " " . $arResult['SEMINARS'][$s_id]['ORG_MAIL'] . " " . $arResult['SEMINARS'][$s_id]['ORG_PHONE'],
                'start_time' => $arResult['SEMINARS'][$s_id]['DISPLAY_BEGIN_DATE'],
                'code' => $arFormResult['BARCODE'],
                'adress' => $arResult['SEMINARS'][$s_id]['ADDRESS'],
                // 'page_href' => $_SERVER['SERVER_NAME'] . "/learn/result.php?find_id=" . $arFormResult['ID'],
                'sending_phone' => $arFormResult['SEND_PHONE']
            );


            $lectors_html_array = array();
            foreach ($arResult['SEMINARS'][$s_id]['LECTURERS'] as $arLecture) {
                $lectors_html_array[] = $arLecture["NAME"] . " / " . "<span class=\"company-reg\">" . $arLecture["COMPANY"] . ", " . $arLecture["POST"] . "</span>";
            }
            $lect = implode(", <br>", $lectors_html_array);


            $html = "
                <html>
                <head>
                   <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
                
                  <style>
                html {
                    font-family: \"DejaVu Sans\";
                }
				.clearfix:after {
					content: \".\";
					 display: block;
					 height: 0;
					 clear: both;
					 visibility: hidden;
				}
				.logo {
					float:right;
				}
                  </style>
                </head>
                 <body>
				 <div class=\"clearfix\" style=\"display:block\">
					<img style=\"position: relative; display:inline-block\" align=\"left\" src=\"{$arFormResult['BARCODE_BASE64']}\" alt=\"\"/>
		<img class=\"logo\" src=\"{$_SERVER["DOCUMENT_ROOT"]}/images/ru-logo-black.png\" alt=\"\"/>
				</div>
                <div style=\"position: relative; display:block\">
				<br>
				<br>
				<br>
				<br>
		
				<br>
				<br>
					<h2>Организатор</h2>
					{$arResult['SEMINARS'][$s_id]['ORG_NAME']} 
					<br>
					Тел.: {$arResult['SEMINARS'][$s_id]['ORG_PHONE']} 
					<br>
					Email: {$arResult['SEMINARS'][$s_id]['ORG_MAIL']}
					<h1>Приглашение на семинар</h1>
					<h3>{$arFormResult['USER_NAME']}</h3>
					<br>	
					<b>Дата и время</b>	<br>
					{$arResult['SEMINARS'][$s_id]['DISPLAY_BEGIN_DATE']}
					<br>	<br>
					<b>Адрес</b>	<br>
					{$arResult['SEMINARS'][$s_id]['ADDRESS']}
					<br>	<br>
					<b>Лекторы семинара</b>	<br>
					{$lect}
					<br>
					<i>* Приглашение дейстивительно только при предъявлении лицом, на которое оно выписано.</i>
				</div>                
				</body>
                </html>
                ";
$html= json_encode(htmlspecialchars($html));
     /*       $dompdf = new \Dompdf\Dompdf();
            $dompdf->load_html($html);
            $dompdf->set_paper('A4', 'portrait');
            $dompdf->render();
            $pdfPath = sys_get_temp_dir() . '/ticket-' . $arFormResult['SEMINAR_ID'] . '-' . uniqid() . '.pdf';
            file_put_contents($pdfPath, $dompdf->output());
*/

	 

            $result = file_get_contents($url, false, stream_context_create(array(
                'http' => array(
                    'method' => 'POST',
                    'header' => 'Content-type: application/x-www-form-urlencoded',
                    'content' => http_build_query($params)
                )
            )));
            $mail = array(
                'org' => $arResult['SEMINARS'][$s_id]['ORG_NAME'] . " " . $arResult['SEMINARS'][$s_id]['ORG_MAIL'] . " " . $arResult['SEMINARS'][$s_id]['ORG_PHONE'],
                'start_time' => $arResult['SEMINARS'][$s_id]['DISPLAY_BEGIN_DATE'],
                'adress' => $arResult['SEMINARS'][$s_id]['ADDRESS'],
                'email' => $arResult['arrAnswersSID'][$_REQUEST['find_id']]['SEMINAR_USER_EMAIL'][0]['USER_TEXT'],
                'seminar_name' => $arResult['SEMINAR_NAME'],
                'name' => $arFormResult['USER_NAME'],
                'FIND_ID' => $_REQUEST['find_id'],
				'html' => $html
				
            );
		
			exec_script('http://'.$_SERVER['SERVER_NAME'].'/ajax/mail_send_seminar_result.php', $mail);

        //    if ($mailResultId = CEvent::Send(1210, 's1', $mail, "Y", 113)) {
       //         $arVALUE[166] = "SENDING";
       //         CFormResult::SetField($_REQUEST['find_id'], 'FILE_SEND', $arVALUE);
       //     }

            //$mailResultId = CEvent::Send("EVENT_USER_REGISTER", "s1", $props);
        /*    $arFile = \CFile::MakeFileArray($pdfPath);
            $arFile["name"] = "Пригласительный билет на " . $arResult['SEMINAR_NAME'] . ".pdf";
            $arFile["MODULE_ID"] = "main";
            $fid = \CFile::SaveFile($arFile, "main");
            $dataAttachment = array(
                'EVENT_ID' => $mailResultId,
                'FILE_ID' => $fid,
            );
            \Bitrix\Main\Mail\Internal\EventAttachmentTable::add($dataAttachment);
*/

        }

    }


}


?>