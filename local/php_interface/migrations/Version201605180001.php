<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 18/05/16
 * Time: 16:50
 */

namespace Sprint\Migration;


class Version201605180001 extends Version
{
    public function up()
    {
        if(!\CModule::IncludeModule("form")) {
            $this->outError("Отсутствует модуль Form");
        } else {
            if (!(new \CForm)->GetByID("MAKE_REQUEST_EN", "Y")->GetNext()) {
                $newFormId = (new \CForm)->Copy(7);
                (new \CForm)->Set([
                    "NAME" => "Make a request",
                    "SID" => "MAKE_REQUEST_EN",
                    "arSITE" => ["en"],
                    "arMENU" => array("en" => "Make a request"),
                ], $newFormId);
                $fieldsRes = \CFormField::GetList($newFormId, "", $by, $order, []);
                while (($field = $fieldsRes->GetNext())) {
                    switch ($field["TITLE"]) {
                        case "Сообщение":
                            $TITLE = "Message";
                            break;
                        case "Фамилия":
                            $TITLE = "Last name";
                            break;
                        case "Имя":
                            $TITLE = "First name";
                            break;
                        case "Отчество":
                            $TITLE = "Second name";
                            break;
                        case "Телефон":
                            $TITLE = "Tel. number";
                            break;
                        default:
                            $TITLE = $field["TITLE"];
                            continue;
                            break;
                    }
                    \CFormField::Set([
                        "FORM_ID" => $field["FORM_ID"],
                        "TITLE" => $TITLE
                    ], $field["ID"]);
                }
            }
            (new \CForm)->Set([
                "SID" => "MAKE_REQUEST_RU"
            ], 7);

            if (!(new \CForm)->GetByID("FEEDBACK_EN", "Y")->GetNext()) {
                $newFormId = (new \CForm)->Copy(1);
                (new \CForm)->Set([
                    "NAME" => "Feedback",
                    "SID" => "FEEDBACK_EN",
                    "arSITE" => ["en"],
                    "arMENU" => array("en" => "Feedback"),
                ], $newFormId);
                $fieldsRes = \CFormField::GetList($newFormId, "", $by, $order, []);
                while (($field = $fieldsRes->GetNext())) {
                    switch ($field["TITLE"]) {
                        case "Тема":
                            $TITLE = "Subject";
                            break;
                        case "Сообщение":
                            $TITLE = "Message";
                            break;
                        case "Фамилия":
                            $TITLE = "Last name";
                            break;
                        case "Имя":
                            $TITLE = "First name";
                            break;
                        case "Отчество":
                            $TITLE = "Second name";
                            break;
                        case "Телефон":
                            $TITLE = "Tel. number";
                            break;
                        default:
                            $TITLE = $field["TITLE"];
                            continue;
                            break;
                    }
                    \CFormField::Set([
                        "FORM_ID" => $field["FORM_ID"],
                        "TITLE" => $TITLE
                    ], $field["ID"]);
                }
                $questionSubject = \CFormField::GetList($newFormId, "", $by, $order, ["TITLE" => "Subject"])->GetNext();
                $answersRes = \CFormAnswer::GetList($questionSubject["ID"], $by, $order, [], $is_filtered);
                while (($answer = $answersRes->GetNext())) {
                    switch ($answer["MESSAGE"]) {
                        case "Запросить расчет по проекту":
                            $message = "Request project payment";
                            break;
                        case "Расчет спецификации":
                            $message = "Calculation specifications";
                            break;
                        case "Сервисное/гарантийное обслуживание":
                            $message = "Service/warranty";
                            break;
                        case "Начать сотрудничество":
                            $message = "Start cooperation";
                            break;
                        case "Прошу перезвонить":
                            $message = "Callback";
                            break;
                        case "Иное":
                            $message = "Other";
                            break;
                        default:
                            continue;
                            break;
                    }

                    \CFormAnswer::Set([
                        "QUESTION_ID" => $questionSubject["ID"],
                        "MESSAGE" => $message
                    ], $answer["ID"]);
                }
            }

        }
    }
}
