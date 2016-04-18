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
        if(!empty($arResult['PROPERTIES']['ORGANIZER']['VALUE'])) {
            $arFilter = Array('ID' => $arResult['PROPERTIES']['ORGANIZER']['VALUE']);
            $res = CIBlockElement::GetList(Array(), $arFilter, false, false, array());

            while($ob = $res->GetNextElement()) {
                $arFields = $ob->GetFields();
                $arFields['props'] = $ob->GetProperties();
                $orgs[] = $arFields;
            }
        }

        if(!empty($orgs)){
            $props['ORG_INFO'] = "Контакты для получения дополнительной информации:<br />";
            foreach($orgs as $org) {
                $props['ORG_INFO'] .= "{$org['NAME']} {$org['props']['MAIL']['VALUE']} {$org['props']['PHONE']['VALUE']}<br/>";
            }
        }

        $props['PRINT_TICKET'] = "/events/result.php?id={$id}";

        $result['success'] = true;
        $result['message'] = "Благодарим Вас за проявленный интерес к нашему мероприятию. <br />";
        if($arEvent['PROPERTIES']['MODERATION']['VALUE'] == 'Y'){
            CEvent::SendImmediate("EVENT_USER_REGISTER_MODERATE", SITE_ID, $props);
        }
        else {
            CEvent::Send("EVENT_USER_REGISTER", SITE_ID, $props);
            $result['redirect'] = "/events/result.php?id={$id}&event={$props['EVENT']}";
            $result['message'] .= print_r($arEvent['PROPERTIES']['WELCOME'], true); //$arEvent['PROPERTIES']['WELCOME']['VALUE'];
        }

    }
    else {
        $result['message'] = $el->LAST_ERROR;
    }
}

echo json_encode($result);