<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 18/05/16
 * Time: 16:50
 */

namespace Sprint\Migration;


class Version201605180002 extends Version
{
    protected $description = "Англ. версия формы \"ЗАЯВКА НА СЕРВИСНОЕ ОБСЛУЖИВАНИЕ\"";

    public function up()
    {
        if(!\CModule::IncludeModule("form")) {
            $this->outError("Отсутствует модуль Form");
        } else {
            if (!(new \CForm)->GetByID("REQUEST_FOR_SERVICE_EN", "Y")->GetNext()) {
                $newFormId = (new \CForm)->Copy(8);
                (new \CForm)->Set([
                    "NAME" => "Request for service",
                    "SID" => "REQUEST_FOR_SERVICE_EN",
                    "arSITE" => ["en"],
                    "arMENU" => array("en" => "Request for service"),
                ], $newFormId);
                $fieldsRes = \CFormField::GetList($newFormId, "", $by, $order, []);
                while (($field = $fieldsRes->GetNext())) {
                    switch ($field["TITLE"]) {
                        case "Требуется":
                            $TITLE = "Request";
                            break;
                        case "Организация":
                            $TITLE = "Company";
                            break;
                        case "Адрес":
                            $TITLE = "Address";
                            break;
                        case "Марка оборудования":
                            $TITLE = "Equipment model";
                            break;
                        case "Заводской номер":
                            $TITLE = "Factory number";
                            break;
                        case "Номер и дата накладных":
                            $TITLE = "Invoices number and date ";
                            break;
                        case "Описание неисправности":
                            $TITLE = "Description";
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

                $questionSubject = \CFormField::GetList($newFormId, "", $by, $order, ["TITLE" => "Request"])->GetNext();
                $answersRes = \CFormAnswer::GetList($questionSubject["ID"], $by, $order, [], $is_filtered);
                while (($answer = $answersRes->GetNext())) {
                    switch ($answer["MESSAGE"]) {
                        case "Пуско-наладочные работы":
                            $message = "start-up programs";
                            break;
                        case "Ремонт":
                            $message = "repairs";
                            break;
                        case "Сервисное оборудование":
                            $message = "service maintenance";
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
            (new \CForm)->Set([
                "SID" => "REQUEST_FOR_SERVICE_RU"
            ], 8);
        }
    }
}
