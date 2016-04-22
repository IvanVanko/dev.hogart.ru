<?php

/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 20/04/16
 * Time: 14:24
 */
namespace Sprint\Migration;

use Sprint\Migration\Helpers\EventHelper;
use Sprint\Migration\Helpers\IblockHelper;

class Version201604220002 extends Version
{
    protected $description = "Изменения формы для задачи 38";

    public function up()
    {
        $EventHelper = new EventHelper();
        $IblockHelper = new IblockHelper();

        if ($IblockHelper->addPropertyIfNotExists(6, [
            "CODE" => "INVITATION_TEXT",
            "NAME" => "Текст подтверждения участия в акции",
            "ACTIVE" => "Y",
            "IS_REQUIRED" => "Y",
            "ROW_COUNT" => 10
        ])) {
            $this->outSuccess("Добавлено свойство \"Текст подтверждения участия в акции\" в Инфоблок \"Акции\"");
        }

        if ($IblockHelper->addPropertyIfNotExists(6, [
            "CODE" => "DECLINE_TEXT",
            "NAME" => "Текст отказа в регистрации на акцию",
            "ACTIVE" => "Y",
            "IS_REQUIRED" => "Y",
            "ROW_COUNT" => 10
        ])) {
            $this->outSuccess("Добавлено свойство \"Текст отказа в регистрации на акцию\" в Инфоблок \"Акции\"");
        }

        if(!\CModule::IncludeModule("form")) {
            $this->outError("Отсутствует модуль Form");
        } else {
            $WEB_FORM_ID = "9";
            $fieldsRes = \CFormField::GetList($WEB_FORM_ID, "", $by, $order, []);
            while (($field = $fieldsRes->GetNext())) {
                switch ($field["TITLE"]) {
                    case "Акция":
                        $SID = "EVENT_NAME";
                        break;
                    case "E-mail":
                        $SID = "EMAIL";
                        break;
                    default:
                        continue;
                        break;
                }
                if (\CFormField::Set([
                    "FORM_ID" => $field["FORM_ID"],
                    "SID" => $SID
                ], $field["ID"])) {
                    $this->outSuccess("В поле \"{$field['TITLE']}\" формы \"Регистрация на акцию\" обновлен идентификатор {$SID}");
                }
            }

            $res = \CFormField::GetList("9", "", $by, $order, [
                "SID" => "EVENT_ID"
            ]);
            if (!($field = $res->GetNext())) {
                $arFields = array(
                    "FORM_ID"				=> $WEB_FORM_ID,
                    "ACTIVE"				=> "Y",
                    "TITLE"					=> "ID Акции",
                    "TITLE_TYPE"			=> "text",
                    "SID"					=> "EVENT_ID",
                    "REQUIRED"				=> "Y",
                    "IN_RESULTS_TABLE"		=> "Y",
                    "IN_EXCEL_TABLE"		=> "Y",
                    "FIELD_TYPE"			=> "integer"
                );
                if (($fieldId = \CFormField::Set($arFields, 0))) {
                    \CFormAnswer::Set([
                        "QUESTION_ID" => $fieldId,
                        "MESSAGE"       => " ",
                        "ACTIVE"        => "Y",
                        "FIELD_TYPE"    => "text"
                    ], 0);
                    $this->outSuccess("Добавлено поле \"{$arFields['TITLE']}\" формы \"Регистрация на акцию\"");
                }
            }
            
            $res = \CFormStatus::GetList($WEB_FORM_ID, $by, $order, [
                "TITLE" => "DEFAULT"
            ]);
            if (($status = $res->GetNext())) {
                if (\CFormStatus::Set([
                    "FORM_ID" => $WEB_FORM_ID,
                    "TITLE" => "На обработке"
                ], $status["ID"])) {
                    $this->outSuccess("Статус \"DEFAULT\" формы \"Регистрация на акцию\" изменен");
                }
            }

            // Добавить статус Подтверждена
            $res = \CFormStatus::GetList($WEB_FORM_ID, $by, $order, [
                "TITLE" => "Подверждена"
            ]);
            if (!($status = $res->GetNext())) {
                $arFields_status = array(
                    "FORM_ID"		=> $WEB_FORM_ID,
                    "C_SORT"		=> 100,
                    "ACTIVE"		=> "Y",
                    "TITLE"			=> "Подверждена",
                    "DESCRIPTION"		=> "Подверждена",
                    "CSS"			=> "statusgreen",
                    "DEFAULT_VALUE"		=> "N",
                    "arPERMISSION_VIEW"	=> array(0),
                    "arPERMISSION_MOVE"	=> array(0),
                    "arPERMISSION_EDIT"	=> array(0),
                    "arPERMISSION_DELETE"	=> array(0),
                );
                if(($id = \CFormStatus::Set($arFields_status, 0))) {
                    $this->outSuccess("Добавлен статус \"Подверждена\"({$id}) формы \"Регистрация на акцию\"");
                    $arTemplates = \CFormStatus::SetMailTemplate($WEB_FORM_ID, $id, "Y", '', true);
                    $arTemplates = reset($arTemplates);
                    if (!empty($arTemplates["FIELDS"]["EVENT_NAME"])) {
                        \CFormStatus::Set([
                            "arMAIL_TEMPLATE" => [[$arTemplates["ID"]]]
                        ], $id);
                        $EventHelper->updateEventMessage($arTemplates["FIELDS"]["EVENT_NAME"], [
                            "SUBJECT" => "Регистрация подтверждена! #EVENT_NAME#, #DATES#",
                            "MESSAGE" => "#INVITATION_TEXT#<br /><br />#URL#",
                            "BODY_TYPE" => "html",
                            "EMAIL_TO" => "#EMAIL#"
                        ]);
                    }
                }
            }
            // Добавить статус Отклонена
            $res = \CFormStatus::GetList($WEB_FORM_ID, $by, $order, [
                "TITLE" => "Отклонена"
            ]);
            if (!($status = $res->GetNext())) {
                $arFields_status = array(
                    "FORM_ID"		=> $WEB_FORM_ID,
                    "C_SORT"		=> 100,
                    "ACTIVE"		=> "Y",
                    "TITLE"			=> "Отклонена",
                    "DESCRIPTION"		=> "Отклонена",
                    "CSS"			=> "statusred",
                    "DEFAULT_VALUE"		=> "N",
                    "arPERMISSION_VIEW"	=> array(0),
                    "arPERMISSION_MOVE"	=> array(0),
                    "arPERMISSION_EDIT"	=> array(0),
                    "arPERMISSION_DELETE"	=> array(0),
                );
                if(($id = \CFormStatus::Set($arFields_status, 0))) {
                    $this->outSuccess("Добавлен статус \"Отклонена\"({$id}) формы \"Регистрация на акцию\"");
                    $arTemplates = \CFormStatus::SetMailTemplate($WEB_FORM_ID, $id, "Y", '', true);
                    $arTemplates = reset($arTemplates);
                    if (!empty($arTemplates["FIELDS"]["EVENT_NAME"])) {
                        \CFormStatus::Set([
                            "arMAIL_TEMPLATE" => [[$arTemplates["ID"]]]
                        ], $id);
                        $EventHelper->updateEventMessage($arTemplates["FIELDS"]["EVENT_NAME"], [
                            "SUBJECT" => "Регистрация не состоялась! #EVENT_NAME#, #DATES#",
                            "MESSAGE" => "#DECLINE_TEXT#<br /><br />#URL#",
                            "BODY_TYPE" => "html",
                            "EMAIL_TO" => "#EMAIL#"
                        ]);
                    }
                }
            }
        }
    }

    public function down(){
        return true;
    }
}