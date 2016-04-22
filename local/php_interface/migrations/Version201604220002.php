<?php

/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 20/04/16
 * Time: 14:24
 */
namespace Sprint\Migration;

use Sprint\Migration\Helpers\EventHelper;

class Version201604220002 extends Version
{
    protected $description = "Изменения формы для задачи 38";

    public function up()
    {
        $EventHelper = new EventHelper();
        if(!\CModule::IncludeModule("form")) {
            $this->outError("Отсутствует модуль Form");
        } else {

            $by = "s_id";
            $order = "asc";
            $WEB_FORM_ID = "9";

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
                        $EventHelper->updateEventMessage($arTemplates["FIELDS"]["EVENT_NAME"], [
                            "SUBJECT" => "Регистрация подтверждена! #EVENT_NAME#, #DATES#",
                            "MESSAGE" => "#INVITATOIN_TEXT#<br /><br />#URL#",
                            "BODY_TYPE" => "html"
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
                        $EventHelper->updateEventMessage($arTemplates["FIELDS"]["EVENT_NAME"], [
                            "SUBJECT" => "Регистрация не состоялась! #EVENT_NAME#, #DATES#",
                            "MESSAGE" => "#DECLINE_TEXT#<br /><br />#URL#",
                            "BODY_TYPE" => "html"
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