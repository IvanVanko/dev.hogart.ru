<?
require($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');

if(!isAjax()){
    LocalRedirect($_SERVER["HTTP_REFERER"]);
}

CModule::IncludeModule('iblock');
$result = array('success' => false);

foreach($_POST['fields'] as $fieldName => $fieldValue){
    $props[strtoupper($fieldName)] = $fieldValue;
}

$iblockId = CIBlock::GetList([], ['CODE' => "event_form_result"])->GetNext()['ID'];
$obEvent = CIBlockElement::GetList([], ['ID' => $props['EVENT']], false, false)->GetNextElement();
if($iblockId && $obEvent){
    $arEvent = $obEvent->GetFields();
    $arEvent['PROPERTIES'] = $obEvent->GetProperties();
    $regCounter = $arEvent['PROPERTIES']['NUMBER']['VALUE'];
    $props['NUMBER'] = ++$regCounter;
    if ($arEvent['PROPERTIES']['MODERATION']['VALUE'] != 'Y') {
        $status = BXHelper::getProperties(array(), array("IBLOCK_ID" => $iblockId,
            "CODE" => "STATUS"), array("ID", "CODE"), "CODE");
        $status = $status["RESULT"]["STATUS"];
        $invitation = BXHelper::getEnumPropertyByXMLId($status['ID'], IBlockHandlers::INVITATION);
        $props["STATUS"] = $invitation["ID"];
    }
    $fields = array(
        "NAME" => implode(" ", [$props['NAME'], $props['SURNAME'], $props['LAST_NAME']]),
        "ACTIVE" => "Y",
        "IBLOCK_ID" => $iblockId,
        "PROPERTY_VALUES" => $props
    );

    $el = new CIBlockElement;

    if($id = $el->Add($fields)){
        CIBlockElement::SetPropertyValuesEx($arEvent['ID'], $arEvent['IBLOCK_ID'], ['NUMBER' => $regCounter]);
        $props['ADDRESS'] = $arEvent['PROPERTIES']['ADDRESS']['VALUE'];
        $props['DATE'] = $arEvent['PROPERTIES']['DATE']['VALUE'];
        $props['URL'] = "//{$_SERVER['NAME']}{$arEvent['DETAIL_PAGE_URL']}";
        $orgs = [];
        if(!empty($arEvent['PROPERTIES']['ORGANIZER']['VALUE'])) {
            $arFilter = Array('ID' => $arEvent['PROPERTIES']['ORGANIZER']['VALUE']);
            $res = CIBlockElement::GetList(Array(), $arFilter, false, false, array());

            while($ob = $res->GetNextElement()) {
                $arFields = $ob->GetFields();
                $arFields['props'] = $ob->GetProperties();
                $orgs[] = $arFields;
            }
        }

        $props['ORG_INFO'] = "";
        if(!empty($orgs)){
            $props['ORG_INFO'] = "По всем вопросам обращаться:<br />";
            foreach($orgs as $org) {
                $props['ORG_INFO'] .= "{$org['NAME']} - {$org['props']['mail']['VALUE']} {$org['props']['phone']['VALUE']}<br/>";
            }
        }

        $props['PRINT_TICKET'] = "/events/result.php?id={$id}";

        ob_start();
        $APPLICATION->IncludeComponent("pirogov:eventRegistrationResult", "", array(
            "ID" => $id
        ));
        $pdf = ob_get_clean();
        define('DOMPDF_ENABLE_AUTOLOAD', false);
        define('DOMPDF_ENABLE_REMOTE', true);
        require $_SERVER['DOCUMENT_ROOT'].'/local/php_interface/include/vendor/dompdf/dompdf/dompdf_config.inc.php';
        $dompdf = new \DOMPDF();
        $dompdf->load_html($pdf);
        $dompdf->render();
        $pdfPath = sys_get_temp_dir() . 'ticket-' . $id . '-' . uniqid() . '.pdf';
        file_put_contents($pdfPath, $dompdf->output());

        $result['success'] = true;
        $result['message'] = "Благодарим Вас за проявленный интерес к нашему мероприятию. <br />";
        if($arEvent['PROPERTIES']['MODERATION']['VALUE'] == 'Y'){
            CEvent::SendImmediate("EVENT_USER_REGISTER_MODERATE", SITE_ID, $props);
        }
        else {
            CEvent::Send("EVENT_USER_REGISTER", SITE_ID, $props, "Y", "", [$pdfPath]);
            $result['redirect'] = "/events/result.php?id={$id}&event={$props['EVENT']}";
            if (!empty($arEvent['PROPERTIES']['WELCOME']['VALUE']['TEXT'])) {
                $result['message'] .= $arEvent['PROPERTIES']['WELCOME']['VALUE']['TEXT'];
            }
        }

    }
    else {
        $result['message'] = $el->LAST_ERROR;
    }
}

echo json_encode($result);