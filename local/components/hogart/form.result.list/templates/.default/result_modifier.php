<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?
jsDump(mb_internal_encoding());
$arSeminarsIds = array();


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
            } else if ($arAnswer['SID'] == 'SEMINAR_USER_CMP') {
                $arrResult['USER_COMPANY'] = $arAnswer['USER_TEXT'];
            } else if ($arAnswer['SID'] == 'SEMINAR_EAN_CODE') {
                if (!isset($arrResult['BARCODE_BASE64']) && strlen($arAnswer['USER_TEXT']) == 12) {
                    $arrResult['BARCODE'] = $arAnswer['USER_TEXT'];
                    $arrResult['BARCODE_BASE64'] = HogartHelpers::generateBarCodeImageData($arAnswer['USER_TEXT']);
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
                $arAddToElement['DISPLAY_BEGIN_DATE'] = FormatDate("d F Yг. / Время начала ", MakeTimeStamp($arElementVariant["PROPERTY_SEM_START_DATE_VALUE"])) . $arElementVariant["PROPERTY_TIME_VALUE"];
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
        $seminar_name = $arElement['NAME'];
        $adress = $arAddToElement['ADDRESS'];
        $start_time = $arAddToElement['DISPLAY_BEGIN_DATE'];
        $org = $arAddToElement['ORG_NAME'] . " " . $arAddToElement['ORG_MAIL'] . " " . $arAddToElement['ORG_PHONE'];
    } else {

        $arElement['ORG_NAME'] = $arElement['PROPERTY_ORG_NAME'];
        $arElement['ORG_MAIL'] = $arElement['PROPERTY_ORG_PROPERTY_MAIL_VALUE'];
        $arElement['ORG_PHONE'] = $arElement['PROPERTY_ORG_PROPERTY_PHONE_VALUE'];
        $arElement['ADDRESS'] = $arElement['PROPERTY_ADDRESS_VALUE'];
	//	var_dump($arElement["PROPERTY_SEM_START_DATE_VALUE"]);
			//die;
		$arElement['DISPLAY_BEGIN_DATE'] = FormatDate("d F Yг. / Время начала ", MakeTimeStamp($arElement["PROPERTY_SEM_START_DATE_VALUE"])) . $arElement["PROPERTY_TIME_VALUE"];

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

if($arResult['FILE_SEND'] == '') {

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
                'seminar_name' => reset($arResult['SEMINARS'])['NAME'],
                'org' => $arResult['SEMINARS'][$s_id]['ORG_NAME'] . " " . $arResult['SEMINARS'][$s_id]['ORG_MAIL'] . " " . $arResult['SEMINARS'][$s_id]['ORG_PHONE'],
                'start_time' => $arResult['SEMINARS'][$s_id]['DISPLAY_BEGIN_DATE'],
                'code' => $arFormResult['BARCODE'],
                'adress' => $arResult['SEMINARS'][$s_id]['ADDRESS'],
               // 'page_href' => $_SERVER['SERVER_NAME'] . "/learn/result.php?find_id=" . $arFormResult['ID'],
                'sending_phone' => $arFormResult['SEND_PHONE']
            );
            $result = file_get_contents($url, false, stream_context_create(array(
                'http' => array(
					'method' => 'POST',
					'header' => 'Content-type: application/x-www-form-urlencoded',
					'content' => http_build_query($params)
				)
            )));
			$mail = array(
				'SEMINAR_ORG' =>  $arResult['SEMINARS'][$s_id]['ORG_NAME'] . " " . $arResult['SEMINARS'][$s_id]['ORG_MAIL'] . " " . $arResult['SEMINARS'][$s_id]['ORG_PHONE'],
				'SEMINAR_START' => $arResult['SEMINARS'][$s_id]['DISPLAY_BEGIN_DATE'],
				'SEMINAR_ADRESS' => $arResult['SEMINARS'][$s_id]['ADDRESS'],
				'SEMINAR_USER_EMAIL' => $arResult['arrAnswersSID'][$_REQUEST['find_id']]['SEMINAR_USER_EMAIL'][0]['USER_TEXT'],
				'SEMINAR_NAME' => reset($arResult['SEMINARS'])['NAME']
			);
		
			if(CEvent::Send(1210, 's1', $mail, "Y", 113)) {
				$arVALUE[166] = "SENDING";
				CFormResult::SetField($_REQUEST['find_id'], 'FILE_SEND', $arVALUE);
			}
		
		}
		
    }
    

   
}







?>