<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/iblock/lib/template/functions/fabric.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/ajax/smsc_api.php');
AddEventHandler("main", "OnBeforeProlog", array("BasicHandlers", "OnBeforePrologHandler"));
AddEventHandler("main", "OnEpilog", array("BasicHandlers", "OnEpilogHandler"));
AddEventHandler("main", "OnBeforeEventAdd", array("BasicHandlers", "OnBeforeEventAddHandler"));

class BasicHandlers {
    function OnEpilogHandler() {
        global $APPLICATION;
        if(
            !defined('ADMIN_SECTION') &&
            defined("ERROR_404") &&
            file_exists($_SERVER["DOCUMENT_ROOT"]."/404.php")
        ) {
            $APPLICATION->RestartBuffer();
            // перетираем выставленные классы, чтобы выглядела страница одинаково везде
            include $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/header.php';
            include $_SERVER['DOCUMENT_ROOT'].'/404.php';
            include $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/footer.php';
        }
    }

    public static function OnBeforePrologHandler() {
        global $APPLICATION;
        if(constant('ADMIN_SECTION')) {
            CJSCore::Init('jquery');
            $APPLICATION->SetAdditionalCSS('/local/admin/assets/plugins.css');
            $APPLICATION->AddHeadScript('/local/admin/assets/plugins.js');
            $APPLICATION->AddHeadScript("/local/admin/assets/admin_basic.js");
        }
    }

    public function OnBeforeEventAddHandler(&$event, &$lid, &$arFields, &$message_id)
    {
    }

}

AddEventHandler("iblock", "OnBeforeIBlockElementAdd", array("IBlockHandlers", "OnBeforeIBlockElementAddHandler"));
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", array("IBlockHandlers", "OnBeforeIBlockElementUpdateHandler"));
AddEventHandler("iblock", "OnAfterIBlockElementAdd", array("IBlockHandlers", "OnAfterIBlockElementAddHandler"));
AddEventHandler("iblock", "OnAfterIBlockElementUpdate", array("IBlockHandlers", "OnAfterIBlockElementUpdateHandler"));

class IBlockHandlers {

    const INVITATION = "INVITATION";
    const DENIED = "DENIED";

    private static function __check_contacts_code_name(&$arParams, $exclude_self = false) {
        if (empty($arParams["CODE"])) {
            $raw_code = $arParams['NAME'];
        } else {
            $raw_code = $arParams["CODE"];
        }

        $code = CUtil::translit($raw_code, 'ru',
            array('change_case' => 'L', 'replace_space' => '-', 'replace_other' => '-'));

        // CODE must be unique
        $filter = array(
            "IBLOCK_ID" => $arParams["IBLOCK_ID"],
            "SECTION_ID" => $arParams["IBLOCK_SECTION_ID"],
            "CODE" => $code);
        if ($exclude_self) {
            $filter["!ID"] = $arParams["ID"];
        }
        $rsItems = CIBlockElement::GetList(array(), $filter, false, false, array("ID"));
        if ($rsItems->AffectedRowsCount()) {
            $code = $code . "_" . $arParams["ID"];
        }
        return $code;
    }

    public static function OnBeforeIBlockElementAddHandler(&$arParams) {
        if ($arParams["IBLOCK_ID"] == CONTACTS_IBLOCK_ID) {
            $arParams["CODE"] = IBlockHandlers::__check_contacts_code_name($arParams);
        }

        if($arParams["IBLOCK_ID"] == SEMINAR_IBLOCK_ID) {
            $ean_number = HogartHelpers::generateSeminarNumber();
            $property = BXHelper::getProperties(array(), array("IBLOCK_ID" => SEMINAR_IBLOCK_ID,
                                                               "CODE" => "sem_ean_id"), array("ID", "CODE"), "CODE");
            $property = $property["RESULT"]["sem_ean_id"];
            $arParams['PROPERTY_VALUES'][$property['ID']]['n0']['VALUE'] = $ean_number;
        }

        if($arParams["IBLOCK_ID"] == CATALOG_IBLOCK_ID) {
            $code = CUtil::translit($arParams['NAME'], 'ru',
                array('change_case' => 'L', 'replace_space' => '-', 'replace_other' => '-'));

            //проверяем наличие элемента с таким же кодом
            $rsItems = CIBlockElement::GetList(array(), array(
                'IBLOCK_ID' => $arParams['IBLOCK_ID'],
                "SECTION_ID" => $arParams['IBLOCK_SECTION_ID'],
                "CODE" => $code
            ), false, false, array('ID', 'IBLOCK_SECTION_ID'));
            if ($rsItems->AffectedRowsCount()) {
                $code = $code . uniqid("_");
            }
            $arParams['CODE'] = $code;
        }
    }

    public static function OnBeforeIBlockElementUpdateHandler(&$arParams) {
        global $APPLICATION;
        $property = BXHelper::getProperties(array(), array("IBLOCK_ID" => SEMINAR_IBLOCK_ID,
                                                           "CODE" => "sem_ean_id"), array("ID", "CODE"), "CODE");
        $property = $property["RESULT"]["sem_ean_id"];

        if ($arParams["IBLOCK_ID"] == CONTACTS_IBLOCK_ID) {
            $arParams["CODE"] = IBlockHandlers::__check_contacts_code_name($arParams, true);
        }

        if($arParams["IBLOCK_ID"] == CATALOG_IBLOCK_ID) {
            $code = CUtil::translit($arParams['NAME'], 'ru',
                array('change_case' => 'L', 'replace_space' => '-', 'replace_other' => '-'));

            //проверяем наличие элемента с таким же кодом
            $rsItems = CIBlockElement::GetList(array(), array(
                'IBLOCK_ID' => $arParams['IBLOCK_ID'],
                "SECTION_ID" => $arParams['IBLOCK_SECTION_ID'],
                "CODE" => $code,
                "!ID" => $arParams["ID"]
            ), false, false, array('ID'));
            if ($rsItems->AffectedRowsCount()) {
                $code = $code . uniqid("_");
            }
            $arParams['CODE'] = $code;
        }

        if(!empty($arParams['PROPERTY_VALUES'][$property['ID']])) {
            foreach($arParams['PROPERTY_VALUES'][$property['ID']] as $key => &$arPropVal) {
                $updated_num = &$arPropVal["VALUE"];
                if(!is_string($updated_num)) {
                    $updated_num = &$arPropVal;
                }
            }
        }

        if($arParams['IBLOCK_ID'] == EVENTS_FORM_RESULT_IBLOCK_ID && $arParams['ID'] > 0) {

            $obEventApplication = CIBlockElement::GetList([], ['ID' => $arParams['ID']],false,false)->GetNextElement();
            $arEventApplication = $obEventApplication->GetFields();
            $arEventApplication['PROPERTIES'] = $obEventApplication->GetProperties();


            $eventCode = BXHelper::getProperties(array(), array("IBLOCK_ID" => EVENTS_FORM_RESULT_IBLOCK_ID,
                                                                "CODE" => "EVENT"), array("ID", "CODE"), "CODE");
            $eventCodeId = $eventCode["RESULT"]['EVENT']['ID'];
            $eventId = reset($arParams['PROPERTY_VALUES'][$eventCodeId])['VALUE'];
            $obEvent = CIBlockElement::GetList([], ['ID' => $eventId],false,false)->GetNextElement();
            $arEvent = $obEvent->GetFields();
            $arEvent['PROPERTIES'] = $obEvent->GetProperties();

            $props['NAME'] = $arEventApplication['PROPERTIES']['NAME']['VALUE'];
            $props['SURNAME'] = $arEventApplication['PROPERTIES']['SURNAME']['VALUE'];
            $props['LAST_NAME'] = $arEventApplication['PROPERTIES']['LAST_NAME']['VALUE'];
            $props['EMAIL'] = $arEventApplication['PROPERTIES']['EMAIL']['VALUE'];
            $props['PHONE'] = $arEventApplication['PROPERTIES']['PHONE']['VALUE'];
            $props['BARCODE'] = $arEventApplication['PROPERTIES']['BARCODE']['VALUE'];

            $props['URL'] = "http://" . ($_SERVER["SERVER_NAME"] ?: $_SERVER['HTTP_HOST']) . "{$arEvent['DETAIL_PAGE_URL']}";
            $props['ADDRESS'] = $arEvent['PROPERTIES']['ADDRESS']['VALUE'];
            $props['DATE'] = $arEvent['PROPERTIES']['DATE']['VALUE'];
            $props['EVENT_NAME'] = $arEvent['NAME'];
            $props['PRINT_TICKET'] = "/events/result.php?id={$arParams['ID']}";

            $status = BXHelper::getProperties(array(), array("IBLOCK_ID" => EVENTS_FORM_RESULT_IBLOCK_ID,
                "CODE" => "STATUS"), array("ID", "CODE"), "CODE");
            $status = $status["RESULT"]["STATUS"];
            $currentStatus = CIBlockElement::GetProperty($arParams['IBLOCK_ID'], $arParams['ID'], [], ['CODE' => "STATUS"])->Fetch();
            if(!empty($arParams['PROPERTY_VALUES'][$status['ID']][0]["VALUE"])
                && $arParams['PROPERTY_VALUES'][$status['ID']][0]["VALUE"] !== $currentStatus['VALUE']
            ) {
                $currentStatus = null;
                if (!empty($arParams['PROPERTY_VALUES'][$status['ID']][0]["VALUE"])) {
                    $currentStatus = BXHelper::getEnumPropertyById($arParams['PROPERTY_VALUES'][$status['ID']][0]["VALUE"]);
                    $currentStatus = $currentStatus["XML_ID"];
                }

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
                if(!empty($orgs)){
                    $props['ORG_INFO'] = "По всем вопросам обращаться:<br />";
                    foreach($orgs as $org) {
                        $props['ORG_INFO'] .= "{$org['NAME']} - {$org['props']['mail']['VALUE']} {$org['props']['phone']['VALUE']}<br/>";
                    }
                }

                switch ($currentStatus) {
                    // подтверждение регистрации
                    case self::INVITATION:
                        ob_start();
                        $APPLICATION->IncludeComponent("pirogov:eventRegistrationResult", "mail-attachment", array(
                            "ID" => $arParams['ID']
                        ));
                        $pdf = ob_get_clean();
                        define('DOMPDF_ENABLE_AUTOLOAD', false);
                        define('DOMPDF_ENABLE_REMOTE', true);
                        require_once $_SERVER['DOCUMENT_ROOT'].'/local/php_interface/include/vendor/dompdf/dompdf/dompdf_config.inc.php';
                        $dompdf = new \DOMPDF();
                        $dompdf->load_html($pdf);
                        $dompdf->set_paper('A4', 'portrait');
                        $dompdf->render();
                        $pdfPath = sys_get_temp_dir() . '/ticket-' . $arParams['ID'] . '-' . uniqid() . '.pdf';
                        file_put_contents($pdfPath, $dompdf->output());

                        $mailResultId = CEvent::Send("EVENT_USER_REGISTER", "s1", $props);
                        $arFile = \CFile::MakeFileArray($pdfPath);
                        $arFile["name"] = "Пригласительный билет на {$props['EVENT_NAME']}.pdf";
                        $arFile["MODULE_ID"] = "main";
                        $fid = \CFile::SaveFile($arFile, "main");
                        $dataAttachment = array(
                            'EVENT_ID' => $mailResultId,
                            'FILE_ID' => $fid,
                        );
                        \Bitrix\Main\Mail\Internal\EventAttachmentTable::add($dataAttachment);

                        $sms_message = "Регистрация подтверждена! {$props['EVENT_NAME']}, {$props['DATE']}, {$props['ADDRESS']}. Код участника: {$props['BARCODE']}. {$props['ORG_INFO']}.\n{$props['URL']}";
                        send_sms($props["PHONE"], strip_tags($sms_message));
                        break;
                    // отказ в регистрации
                    case self::DENIED:
                        $props['TEXT'] = $arEvent['PROPERTIES']['DENIED_TEXT']['VALUE'];
                        CEvent::Send("EVENT_USER_REGISTER_DENIED", "s1", $props);
                        $sms_message = "{$props['EVENT_NAME']}, {$props['DATE']}, {$props['ADDRESS']}. {$props['TEXT']}. {$props['ORG_INFO']}";
                        send_sms($props["PHONE"], strip_tags($sms_message));
                        break;
                    default:
                        break;
                }
            }
        }
    }

    public static function OnAfterIBlockElementAddHandler(&$arParams) {
        if($arParams['IBLOCK_ID'] == EVENTS_IBLOCK_ID) {
            self::insertEventLink($arParams);
        }
    }

    public static function OnAfterIBlockElementUpdateHandler(&$arParams) {
        if($arParams['IBLOCK_ID'] == EVENTS_IBLOCK_ID) {
            self::insertEventLink($arParams);
        }
    }

    private static function insertEventLink($arParams) {
        $elem = CIBlockElement::GetByID($arParams['ID'])->GetNext();
        CIBlockElement::SetPropertyValuesEx($arParams['ID'], $arParams['IBLOCK_ID'],
            array('LINK' => "//".$_SERVER['SERVER_NAME'].$elem['DETAIL_PAGE_URL']));
    }
}

use Bitrix\Main;

$eventManager = Main\EventManager::getInstance();
$eventManager->addEventHandler("iblock", "OnTemplateGetFunctionClass", array("IPropertyHandlers",
                                                                             "OnTemplateGetFunctionClassHandler"));

class IPropertyHandlers {

    public static function OnTemplateGetFunctionClassHandler(Bitrix\Main\Event $event) {
        $arParam = $event->getParameters();
        $functionClass = $arParam[0];
        if(is_string($functionClass) && class_exists($functionClass)) {
            $result = new Bitrix\Main\EventResult(1, $functionClass);
            return $result;
        }
    }

}

class custom_concat_function extends Bitrix\Iblock\Template\Functions\FunctionBase {

    public function concatElementResult($iblock_id, $select, $elements, $properties) {
        $element_sections = array();
        $result = array();
        $arEnum = array();
        $enum_fields = array();
        $link_element_fields = array();
        foreach($select as &$select_field) {
            /* if ($key == 'PROPERTIES') { */
            /* foreach ($select_field as $field) { */
            if(preg_match("/PROPERTY_(\w+)/", $select_field, $match)) {
                //$arProperty = BXHelper::getProperties(array(), array('CODE' => $match[1], 'IBLOCK_ID' => $iblock_id), array(), 'CODE');
                $arProperty = $properties['RESULT'][$match[1]];
                if($arProperty['PROPERTY_TYPE'] == 'E') {
                    $link_element_fields[] = $select_field;
                }
                else if($arProperty['PROPERTY_TYPE'] == 'L') {
                    $enum_fields[] = $select_field;
                    $arEnum[$select_field] = BXHelper::getEnum($arProperty['ID'], array(), array(), 'ID');
                }
            }
            /* } */
            /* } */
        }

        foreach($select as $key => &$select_field) {
            /* if ($key === 'PROPERTIES') { */
            /* foreach ($select_field as $fkey => $field_name) {$link_element_id
              foreach ($elements as $elem) {
              $arPropertyValue = $elem['PROPERTIES'][$field_name];
              if ($arPropertyValue['PROPERTY_TYPE'] == "E" || $arPropertyValue['PROPERTY_TYPE'] == "L") {
              $result[] = $arEnum[$field_name][$arPropertyValue['VALUE']]['VALUE'];
              } else {
              $result[] = $arPropertyValue['VALUE'];
              }
              }
              } */
            /* } else { */
            foreach($elements as $elem) {
                if($select_field == 'SECTION_CODE_PATH') {
                    $sections = BXHelper::getSectionPath($elem['IBLOCK_ID'], $elem['IBLOCK_SECTION_ID'], array('NAME',
                                                                                                               'ID',
                                                                                                               'DEPTH_LEVEL'));
                    foreach($sections['RESULT'] as $arSect) {
                        $element_sections[] = $arSect;
                    }
                }
                else if(in_array($select_field, $enum_fields)) {
                    $result[] = $arEnum[$select_field][$elem[$select_field."_VALUE"]];
                }
                else if(in_array($select_field, $link_element_fields)) {
                    $result[] = $elem[strtoupper(str_replace(".NAME", "", $select_field))."_NAME"];
                }
            }
            /* } */
        }
        if(!empty($element_sections)) {
            $element_sections = BXHelper::complex_sort($element_sections, array('DEPTH_LEVEL' => 'ASC'), false);
            foreach($element_sections as $sect) {
                $result[] = $sect['NAME'];
            }
        }
        return implode(", ", array_unique(array_filter($result)));
    }

    public function concatSectionResult($iblock_id, $select, $sections) {
        $result = array();
        foreach($select as $key => &$select_field) {
            if($key === 'PROPERTIES') {
                /* foreach ($select_field as $fkey => $field_name) {
                  foreach ($sections as $sect) {
                  $arPropertyValue = $sect['PROPERTIES'][$field_name];
                  if ($arPropertyValue['PROPERTY_TYPE'] == "E" || $arPropertyValue['PROPERTY_TYPE'] == "L") {
                  $result[] = $arEnum[$field_name][$arPropertyValue['VALUE']]['VALUE'];
                  } else {
                  $result[] = $arPropertyValue['VALUE'];
                  }
                  }
                  } */
            }
            else {
                foreach($sections as $sect) {
                    /* if ($select_field == 'SECTION_CODE_PATH') {
                      $sections = BXHelper::getSectionPath($sect['IBLOCK_ID'], $sect['IBLOCK_SECTION_ID'], array('NAME', 'ID','DEPTH_LEVEL'));
                      foreach ($sections['RESULT'] as $arSect) {
                      $element_sections[] = $arSect;
                      }
                      } */
                    $result[] = $sect[$select_field];
                }
            }
        }
        /* if (!empty($element_sections)) {
          $element_sections = BXHelper::complex_sort($element_sections, array('DEPTH_LEVEL' => 'ASC'), false);
          foreach ($element_sections as $sect) {
          $result[] = $sect['NAME'];
          }
          } */
        return implode(", ", array_unique(array_filter($result)));
    }

}

class concat_elements_property extends custom_concat_function {

    public function onPrepareParameters(\Bitrix\Iblock\Template\Entity\Base $entity, $parameters) {
        $arguments = array();
        $arElementSelect = array();
        /** @var \Bitrix\Iblock\Template\NodeEntityField $parameter */
        $formated_params = array();
        $properties = array();
        foreach($parameters as $parameter) {

            $value = explode(".", BXHelper::getProtectedProperty($parameter, 'entityField'));

            foreach($value as &$val) {
                if($val == 'iblock_id') {
                    $val = strtoupper($val);
                }
            }

            if($value[0] == 'elements') {
                if($value[1] == 'property') {
                    $properties[] = $value[2];
                }
            }
        }
        $arProperties = BXHelper::getProperties(array(), array('CODE' => $properties), array('PROPERTY_TYPE',
                                                                                             'CODE',
                                                                                             'LINK_IBLOCK_ID'), 'CODE');
        $arguments['PROPERTIES'] = $arProperties;
        foreach($parameters as $parameter) {

            //хак. в методе process ошибка. при указание параметров в SEO модуле они принудительно приводятся к strtolower
            //при этом такие параметры как IBLOCK_ID и IBLOCK_SECTION_ID в массивах fieldMap и fields у объекта Base находятся с ассоциативном массивом с ключами КАПСОМ
            //таким образом невозможно в кастомном обработчике мета тегов получить ничего кроме полей предусмотренных в SEO модуле (они хранятся как и задумано - маленькими буквами), хотя инфа о них в объекте хранится
            //ID получить нельзя, но можно получить IBLOCK_ID и CODE, таким образом задаемся условием, что у нас пара (IBLOCK_ID + CODE) определят уникальный раздел CIBlockSection
            //таким образом можем сформировать для такой категории мета тег на основе свойств элемента.
            //для изменения значения entityField используем ReflectionClass
            //также ReflectionClass нужен для получения идентификаторов других кастомных параметров, например elements.property.brand
            $value = explode(".", BXHelper::getProtectedProperty($parameter, 'entityField'));

            foreach($value as &$val) {
                if($val == 'iblock_id') {
                    $val = strtoupper($val);
                }
            }

            if($value[0] == 'elements') {
                if($value[1] == 'property') {
                    if($arProperties['RESULT'][$value[2]]['PROPERTY_TYPE'] == 'E') {
                        $arElementSelect[] = "PROPERTY_".$value[2].".NAME";
                    }
                    else {
                        $arElementSelect[] = "PROPERTY_".$value[2];
                    }
                }
                if($value[1] == 'field') {
                    $arElementSelect[] = strtoupper($value[2]);
                }
            }
            else if($value[0] == 'this') {
                if(count($value) == 2) {
                    BXHelper::setProtectedProperty($parameter, 'entityField', implode(".", $value));
                    //$property->setValue($parameter, implode(".",$value));
                    $arguments[$value[1]] = $parameter->process($entity);
                }
                else if(count($value) == 3) {
                    $arguments[$value[1]][$value[2]] = $parameter->process($entity);
                }
            }
        }
        if(!empty($arElementSelect)) {
            $arguments['SELECT'] = $arElementSelect;
            return $arguments;
        }
        else {
            return array();
        }
    }

    public function calculate(array $parameters) {
        if(count($parameters['SELECT']) && !empty($parameters['code']) && intval($parameters['IBLOCK_ID'])) {
            $select = $parameters['SELECT'];
            $elements = BXHelper::getElements(
                array(), array('IBLOCK_ID' => $parameters['IBLOCK_ID'],
                               'SECTION_CODE' => $parameters['code'],
                               "INCLUDE_SUBSECTIONS" => "Y"), false, false, $select);
            $elements = $elements['RESULT'];

            return parent::concatElementResult($parameters['IBLOCK_ID'], $select, $elements, $parameters['PROPERTIES']);
        }
        return "";
    }

}

class concat_linked_to_elements extends custom_concat_function {

    public function onPrepareParameters(\Bitrix\Iblock\Template\Entity\Base $entity, $parameters) {
        $arguments = array();
        $arElementSelect = array();
        $linked_property_code = "";
        /** @var \Bitrix\Iblock\Template\NodeEntityField $parameter */
        foreach($parameters as $parameter) {
            $reflection = new ReflectionClass($parameter);
            $property = $reflection->getProperty('entityField');
            $property->setAccessible(true);
            $value = explode(".", $property->getValue($parameter));

            foreach($value as &$val) {
                if($val == 'iblock_id') {
                    $val = strtoupper($val);
                }
            }

            if($value[0] == 'elements') {
                if($value[1] == 'property') {
                    $arElementSelect[] = "PROPERTY_".$value[2];
                }
                if($value[1] == 'field') {
                    $arElementSelect[] = strtoupper($value[2]);
                }
            }
            else if($value[0] == 'linked_property') {
                $arElementSelect[] = "PROPERTY_".$value[1];
                $properties[] = $linked_property_code = $value[1];
            }
            else if($value[0] == 'this') {
                if(count($value) == 2) {
                    $property->setValue($parameter, implode(".", $value));
                    $arguments[$value[1]] = $parameter->process($entity);
                }
                else if(count($value) == 3) {
                    $arguments[$value[1]][$value[2]] = $parameter->process($entity);
                }
            }
        }
        if(!empty($arElementSelect) && !empty($linked_property_code)) {
            $arguments['SELECT'] = $arElementSelect;
            $arguments['LINKED_PROPERTY_CODE'] = $linked_property_code;
            if(!empty($arguments['IBLOCK_ID'])) {
                $property = BXHelper::getProperties(array(), array('CODE' => $linked_property_code,
                                                                   'LINK_IBLOCK_ID' => $arguments['IBLOCK_ID']), array());
                //принимаем условие что пара CODE + LINK_IBLOCK_ID уникальна у каждого свойства
                $property = current($property['RESULT']);
                $arguments['LINKED_IBLOCK_ID'] = $property['IBLOCK_ID'];
            }
            $arProperties = BXHelper::getProperties(array(), array('CODE' => $properties), array('PROPERTY_TYPE',
                                                                                                 'CODE',
                                                                                                 'LINK_IBLOCK_ID'), 'CODE');
            $arguments['PROPERTIES'] = $arProperties;
            return $arguments;
        }
        else {
            return array();
        }
    }

    public function calculate(array $parameters) {
        if(count($parameters['SELECT']) && !empty($parameters['LINKED_PROPERTY_CODE']) && !empty($parameters['LINKED_IBLOCK_ID']) && !empty($parameters['code'])) {


            $select = $parameters['SELECT'];

            $linked_property_code = $parameters['LINKED_PROPERTY_CODE'];
            $linked_iblock_id = $parameters['LINKED_IBLOCK_ID'];

            unset($parameters['SELECT']);
            unset($parameters['LINKED_PROPERTY_CODE']);
            unset($parameters['LINKED_PROPERTY_ID']);


            $this_element = BXHelper::getElements(
                array(), array('IBLOCK_ID' => $parameters['IBLOCK_ID'],
                               'CODE' => $parameters['code']), false, false, array('ID', 'CODE'), true, 'CODE');

            $link_element_id = $this_element['RESULT'][$parameters['code']]['ID'];


            if(in_array('SECTION_CODE_PATH', $select)) {
                $select[] = 'IBLOCK_ID';
                $select[] = 'ID';
                $select[] = 'IBLOCK_SECTION_ID';
            }
            $elements = BXHelper::getElements(array(), array('PROPERTY_'.$linked_property_code => $link_element_id,
                                                             'IBLOCK_ID' => $linked_iblock_id), false, false, $select);
            $elements = $elements['RESULT'];

            return parent::concatElementResult($linked_iblock_id, $select, $elements, $parameters['PROPERTIES']);
        }
        return "";
    }

}

class concat_subsections extends custom_concat_function {

    public function onPrepareParameters(\Bitrix\Iblock\Template\Entity\Base $entity, $parameters) {
        $arguments = array();
        $arSectionSelect = array();

        foreach($parameters as $parameter) {
            /** @var \Bitrix\Iblock\Template\NodeEntityField $parameter */
            $reflection = new ReflectionClass($parameter);
            $property = $reflection->getProperty('entityField');
            $property->setAccessible(true);
            $value = explode(".", $property->getValue($parameter));

            foreach($value as &$val) {
                if($val == 'iblock_id') {
                    $val = strtoupper($val);
                }
            }

            if($value[0] == 'section') {
                if($value[1] == 'property') {
                    $arSectionSelect['PROPERTIES'][] = "uf_".$value[2];
                }
                if($value[1] == 'field') {
                    $arSectionSelect[] = strtoupper($value[2]);
                }
            }
            else if($value[0] == 'this') {
                if(count($value) == 2) {
                    $property->setValue($parameter, implode(".", $value));
                    $arguments[$value[1]] = $parameter->process($entity);
                }
                else if(count($value) == 3) {
                    $arguments[$value[1]][$value[2]] = $parameter->process($entity);
                }
            }
        }

        if(!empty($arSectionSelect) && !empty($arguments['IBLOCK_ID']) && !empty($arguments['code'])) {
            $arguments['SELECT'] = $arSectionSelect;
            return $arguments;
        }
        else {
            return array();
        }
    }

    public function calculate(array $parameters) {
        if(count($parameters['SELECT']) && !empty($parameters['code']) && intval($parameters['IBLOCK_ID'])) {
            $select = $parameters['SELECT'];
            $parent_section = BXHelper::getSections(
                array(), array('IBLOCK_ID' => $parameters['IBLOCK_ID'],
                               'CODE' => $parameters['code'],
                               "INCLUDE_SUBSECTIONS" => "Y"), false, array(), true, 'CODE');
            $parent_section = $parent_section['RESULT'][$parameters['code']];
            $subsections = BXHelper::getSections(array(), array(
                '>LEFT_MARGIN' => intval($parent_section['LEFT_MARGIN']) + 1,
                "<RIGHT_MARGIN" => intval($parent_section['RIGHT_MARGIN']) - 1,
                "IBLOCK_ID" => $parameters["IBLOCK_ID"]
            ), false, array());
            $subsections = $subsections['RESULT'];
            return parent::concatSectionResult($parameters['IBLOCK_ID'], $select, $subsections);
        }
        return "";
    }

}

AddEventHandler("subscribe", "BeforePostingSendMail", Array("MyCustomClass", "BeforePostingSendMailHandler"));

class MyCustomClass {

    function BeforePostingSendMailHandler($arFields) {
        include_once $_SERVER["DOCUMENT_ROOT"]."/ajax/smsc_api.php";


        $sms_text = (string)getNodeContentByID($arFields["BODY"], "sms_send_block");
        $arFields["BODY"] = removeNodeByID($arFields["BODY"], "sms_send_block");
        $list = \CUSTOM\Entity\SubscribeSettingsTable::GetList(array("filter" => array("UF_SUBSCRIBER_ID" => $arFields["EMAIL_EX"]["SUBSCRIPTION_ID"])));
        if($el = $list->fetch()) {
            $phone = trim(stripslashes($el["UF_SUBSCRIBER_PHONE"]));
            $phone_length = strlen(preg_replace('/[^0-9.]+/', '', $phone));
            $phone = preg_replace('/[^0-9.+]+/', '', $phone);
        }

        if($phone_length == 11 && false) {
            $r = send_sms($phone, $sms_text, 0);
        }

        return $arFields;
    }

}


AddEventHandler("form", "onBeforeResultAdd", Array("FormHandlers", "OnBeforeResultAddHandler"));
AddEventHandler("form", "onAfterResultAdd", Array("FormHandlers", "OnAfterResultAddHandler"));
AddEventHandler("form", "onBeforeResultStatusChange", Array("FormHandlers", "onBeforeResultStatusChangeHandler"));

class FormHandlers {
    public static function OnBeforeResultAddHandler($id, $form_fields, &$arrVALUES) {
        CModule::IncludeModule('iblock');
        if($id == 5) {
            $matches = array();
            $ean_seminar_num = false;
            $fields = BXHelper::getFormFields($id, array('SORT' => 'ASC'), array('SID' => 'SEMINAR_EAN_CODE'));
            $field_id = $fields[0]['ID'];
            $ean_request_key = false;
            foreach($arrVALUES as $key => $value) {
                if(preg_match("/.+?(\d+$)/", $key, $matches)) {
                    $answer_id = $matches[1];
                    if(intval($value)) {
                        $answers = BXHelper::getFormAnswers($field_id, array('SORT' => 'ASC'), array('ID' => $answer_id));
                        if(!empty($answers[0]['ID'])) {
                            $ean_seminar_num = $value;
                            $ean_request_key = $key;
                            break;
                        }
                    }
                }
            }
            if($ean_seminar_num) {
                $obElement = new CIBlockElement();
                $seminar = BXHelper::getElements(array(), array('IBLOCK_ID' => SEMINAR_IBLOCK_ID,
                                                                'PROPERTY_sem_ean_id' => $ean_seminar_num), false, false, array('ID',
                                                                                                                                'PROPERTY_sem_visitors_count',
                                                                                                                                'PROPERTY_sem_start_date'), false);
                $visitors_count = $seminar['RESULT'][0]['PROPERTY_SEM_VISITORS_COUNT_VALUE'];
                $next_visitor_num = sprintf("%03d", ++$visitors_count);
                $ean_visitor_num = $ean_seminar_num.$next_visitor_num;
                $arrVALUES[$ean_request_key] = $ean_visitor_num;
                $obElement->SetPropertyValuesEx($seminar['RESULT'][0]['ID'], SEMINAR_IBLOCK_ID, array('sem_visitors_count' => $visitors_count));
            }
        }
        return true;
    }

    public function onBeforeResultStatusChangeHandler($form_id, $result_id, &$new_status_id, $check_rights = "Y")
    {
        // Обработка формы Регистрация на акцию
        if ($form_id == "9") {
            $WEB_FORM_ID = "9";
            $res = \CFormStatus::GetList($WEB_FORM_ID, $by, $order, [
                "ACTIVE" => "Y"
            ]);
            $statuses = [];
            while (($status = $res->GetNext())) {
                $statuses[$status["ID"]] = $status;
            }

            if (intval($result_id) > 0) {
                
                \CForm::GetResultAnswerArray($WEB_FORM_ID,
                    $arrColumns,
                    $arrAnswers,
                    $arrAnswersVarname, [
                        "RESULT_ID" => $result_id
                    ]
                );
                $eventElement = \CIBlockElement::GetByID($arrAnswersVarname[$result_id]["EVENT_ID"][0]["USER_TEXT"])->GetNextElement();
                $arEvent = $eventElement->GetFields();
                $arEvent["PROPERTIES"] = $eventElement->GetProperties();

                $arFields["EMAIL"] = $arrAnswersVarname[$result_id]["EMAIL"][0]["USER_TEXT"];
                $arFields["EVENT_NAME"] = htmlspecialchars_decode($arEvent["NAME"]);
                $arFields["DATES"] = FormatDate("d.m.Y", MakeTimeStamp($arEvent["DATE_ACTIVE_FROM"])) . " - " . FormatDate("d.m.Y", MakeTimeStamp($arEvent["DATE_ACTIVE_TO"]));

                $arFields["INVITATION_TEXT"] = $arEvent["PROPERTIES"]["INVITATION_TEXT"]["VALUE"];
                $arFields["DECLINE_TEXT"] = $arEvent["PROPERTIES"]["DECLINE_TEXT"]["VALUE"];
                $arFields['URL'] = "http://" . ($_SERVER["SERVER_NAME"] ?: $_SERVER['HTTP_HOST']) . "{$arEvent['DETAIL_PAGE_URL']}";

                if (!empty($arEvent["PROPERTIES"]["ORG"]["VALUE"])) {
                    $arFields["ORGS"] = "По дополнительным вопросам просим обращаться к ответственным за проведение: <br>";

                    $res = CIBlockElement::GetList(Array(), ["ID" => $arEvent["PROPERTIES"]["ORG"]["VALUE"]], false, false, array());
                    while ($ob = $res->GetNextElement()) {
                        $org = $ob->GetFields();
                        $org['props'] = $ob->GetProperties();
                        $arFields["ORGS"] .= "{$org['NAME']}, {$org['props']['phone']['VALUE']}, {$org['props']['mail']['VALUE']}<br>";
                    }
                }

                switch ($statuses[$new_status_id]["TITLE"]) {
                    case "Подверждена":
                        $sms_message = "Регистрация подтверждена! {$arFields['EVENT_NAME']}, {$arFields['DATES']}.\n{$arFields['INVITATION_TEXT']}\n{$arFields['URL']}";
                        send_sms($arrAnswersVarname[$result_id]["PHONE"][0]["USER_TEXT"], strip_tags($sms_message));
                        break;
                    case "Отклонена":
                        $sms_message = "Регистрация не состоялась! {$arFields['EVENT_NAME']}, {$arFields['DATES']}.\n{$arFields['DECLINE_TEXT']}\n{$arFields['URL']}";
                        send_sms($arrAnswersVarname[$result_id]["PHONE"][0]["USER_TEXT"], strip_tags($sms_message));
                        break;
                }

                $dbRes = CFormStatus::GetByID($new_status_id);
                if (($arStatus = $dbRes->Fetch()) && strlen($arStatus['MAIL_EVENT_TYPE'])) {
                    $arTemplates = CFormStatus::GetMailTemplateArray($new_status_id);
                    if (is_array($arTemplates) && count($arTemplates)) {
                        $dbRes = CEventMessage::GetList($by="id", $order="asc", array(
                            'ID' => implode('|', $arTemplates),
                            "ACTIVE"		=> "Y",
                            "EVENT_NAME"	=> $arStatus["MAIL_EVENT_TYPE"]
                        ));
                        while ($arTemplate = $dbRes->Fetch())
                            CEvent::Send($arTemplate["EVENT_NAME"], $arTemplate["SITE_ID"], $arFields, "Y", $arTemplate["ID"]);
                    }
                }
            }
        }
    }
}

AddEventHandler("main", "OnAdminTabControlBegin", array("DisplayHandlers", "MyOnAdminTabControlBegin"));
AddEventHandler("main", "OnEndBufferContent", array("DisplayHandlers", "MyOnEndBufferContent"));

class DisplayHandlers {

    /**
     * @param $form CAdminForm
     */
    public static function MyOnAdminTabControlBegin(&$form) {

        if($GLOBALS["APPLICATION"]->GetCurPage() == "/bitrix/admin/subscr_edit.php") {

            $subscribe_settings = \CUSTOM\Entity\SubscribeSettingsTable::GetList(array(
                    'select' => array('*', "UF_SUBSCRIBER_PHONE"),
                    'filter' => array('UF_SUBSCRIBER_ID' => $_GET['ID'])
                )
            )->fetch();

            $html = '<tr class="adm-detail-field">
		<td class="adm-detail-content-cell-l">Телефон подписчика:</td>
		<td class="adm-detail-content-cell-r"><input type="text" name="entity[UF_SUBSCRIBER_PHONE]" value="'.(($_POST["entity"]["UF_SUBSCRIBER_PHONE"]) ? $_POST["entity"]["UF_SUBSCRIBER_PHONE"] : $subscribe_settings["UF_SUBSCRIBER_PHONE"]).'" size="30" maxlength="255"></td>
	</tr>';

            $form->tabs[] = array("DIV" => "my_edit",
                                  "TAB" => "Дополнительно",
                                  "ICON" => "main_user_edit",
                                  "TITLE" => "Дополнительно",
                                  "CONTENT" =>
                                      $html
            );
        }
    }

    public static function MyOnEndBufferContent(&$content) {
        if($GLOBALS["APPLICATION"]->GetCurPage() == "/bitrix/admin/form_result_edit.php") {
            if($_REQUEST["WEB_FORM_ID"] == "5") {
                $regexp = SEMINAR_LINK_INPUT_REGEXP;
                if(preg_match("/$regexp/", $content, $matches)) {

                    $obHtmlNode = Sunra\PhpSimple\HtmlDomParser::str_get_html(BXHelper::utf8_to_entities($matches[0]))->firstChild();
                    $input = array_shift($obHtmlNode->find('input'));
                    $input->setAttribute('readonly', 'true');
                    $seminar_id = $input->getAttribute('value');

                    $link = BXHelper::getIblockAdminLink(SEMINAR_IBLOCK_ID, $seminar_id, 'training');

                    $element = BXHelper::getElements(array(), array('ID' => $seminar_id), false, false, array('ID',
                                                                                                              'NAME'), true, 'ID');
                    $element_name = $element['RESULT'][$seminar_id]['NAME'];
                    $append_link = "<tr><td valign=\"top\" class=\"adm-detail-content-cell-l\">
Ссылка на семинар</td><td class=\"adm-detail-content-cell-r\"><a  target=\"_blank\" href=\"".$link."\">$element_name</a></td></tr>";
                    $content = preg_replace("/($regexp)/", "$1$append_link", $content);
                }
            }
        }
    }

}

AddEventHandler("subscribe", "OnBeforeSubscriptionAdd", array("SubscribeHandlers", "OnBeforeSubscriptionAddHandler"));
AddEventHandler("subscribe", "OnBeforeSubscriptionUpdate", array("SubscribeHandlers",
                                                                 "OnBeforeSubscriptionUpdateHandler"));

class SubscribeHandlers {

    public static function OnBeforeSubscriptionAddHandler($arFields) {
        $arCustomSubscriptionFields = CStorage::GetVar("arCustomSubscriptionFields");
        if(!is_array($arCustomSubscriptionFields)) {
            $arCustomSubscriptionFields = array();
        }
        $arCustomSubscriptionFields[$arFields["EMAIL"]] = array("PHONE" => $_POST['entity']['UF_SUBSCRIBER_PHONE'],
                                                                "EMAIL" => $arFields["EMAIL"]);
        CStorage::SetVar($arCustomSubscriptionFields, "arCustomSubscriptionFields");
        register_shutdown_function(function () {
            $arCustomSubscriptionFields = CStorage::GetVar("arCustomSubscriptionFields");
            foreach($arCustomSubscriptionFields as $key => $field) {
                $subscription = CSubscription::GetByEmail($field["EMAIL"])->fetch();
                if(array_key_exists($subscription["EMAIL"], $arCustomSubscriptionFields)) {
                    \CUSTOM\Entity\SubscribeSettingsTable::Add(array("UF_SUBSCRIBER_ID" => $subscription["ID"],
                                                                     "UF_SUBSCRIBER_PHONE" => $field["PHONE"]));
                }
                unset($arCustomSubscriptionFields[$key]);
            }
            CStorage::SetVar($arCustomSubscriptionFields, "arCustomSubscriptionFields");
        });
    }

    public static function OnBeforeSubscriptionUpdateHandler($arFields) {

        $subscriber_phone = (string)$_POST['entity']['UF_SUBSCRIBER_PHONE'];

        $fields = array();

        if($subscriber_phone) {
            $fields['UF_SUBSCRIBER_PHONE'] = $subscriber_phone;
        }

        if($subscriber_phone) {
            $settings_entry = \CUSTOM\Entity\SubscribeSettingsTable::GetList(array(
                'select' => array('ID'),
                'filter' => array('UF_SUBSCRIBER_ID' => $arFields["ID"])
            ))->fetch();

            \CUSTOM\Entity\SubscribeSettingsTable::Update($settings_entry['ID'], $fields);
        }
    }

}
