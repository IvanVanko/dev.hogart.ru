<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)	die();?>
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
    $arrResult['USER_NAME'] = $seminar_user_name." ".$seminar_user_mid_name." ".$seminar_user_last_name;
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
        'PROPERTY_address'
    ), true, 'ID');

$elements = $elements['RESULT'];

foreach ($elements as &$arElement) {
	        $seminar_name = $arElement['NAME'];
    if (!is_assoc($arElement)) {
        $arAddToElement = array();
        foreach ($arElement as $arElementVariant) {
            $arAddToElement['BRANDS'][] = $arElementVariant['PROPERTY_BRAND_NAME'];
            $arAddToElement['ORG_NAME'] = $arElementVariant['PROPERTY_ORG_NAME'];
            $arAddToElement['ORG_MAIL'] = $arElementVariant['PROPERTY_ORG_PROPERTY_MAIL_VALUE'];
            $arAddToElement['ORG_PHONE'] = $arElementVariant['PROPERTY_ORG_PROPERTY_PHONE_VALUE'];
            $arAddToElement['ADDRESS'] = $arElementVariant['PROPERTY_ADDRESS_VALUE'];
            if (!isset($arAddToElement['DISPLAY_BEGIN_DATE'])) {
                $arAddToElement['DISPLAY_BEGIN_DATE'] = FormatDate("d F Yг. / Время начала H:i", MakeTimeStamp($arElementVariant["PROPERTY_SEM_START_DATE_VALUE"]));
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
    } else {

        $arElement['ORG_NAME'] = $arElement['PROPERTY_ORG_NAME'];
        $arElement['ORG_MAIL'] = $arElement['PROPERTY_ORG_PROPERTY_MAIL_VALUE'];
        $arElement['ORG_PHONE'] = $arElement['PROPERTY_ORG_PROPERTY_PHONE_VALUE'];
        $arElement['ADDRESS'] = $arElement['PROPERTY_ADDRESS_VALUE'];
        $arElement['DISPLAY_BEGIN_DATE'] = FormatDate("d F Yг. / Время начала H:i", MakeTimeStamp($arElement["PROPERTY_SEM_START_DATE_VALUE"]));
        $arElement['BRANDS'] = array('PROPERTY_BRAND_NAME');
        $lecturer_id = $arElement['PROPERTY_LECTURER_VALUE'];
        $arElement['LECTURERS'][$lecturer_id] = array(
            'NAME' => $arElement['PROPERTY_LECTURER_NAME'],
            'POST' => $arElement['PROPERTY_LECTURER_PROPERTY_STATUS_VALUE'],
            'COMPANY' => $arElement['PROPERTY_LECTURER_PROPERTY_COMPANY_VALUE'],
        );
    }
}


$url = 'http://'.$_SERVER['SERVER_NAME'] . '/ajax/smsc_send_seminar_result.php';
$params = array(
    'seminar_name' => $seminar_name, 
    'page_href' => $_SERVER['SERVER_NAME']."/learn/result.php?find_id=" . $arrResult['SEMINAR_ID'],
    'sending_phone' => $sending_phone
);
$result = file_get_contents($url, false, stream_context_create(array(
    'http' => array(
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'content' => http_build_query($params)
    )
)));

$arResult['SEMINARS'] = $elements;
?>