<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 18/05/16
 * Time: 16:50
 */

namespace Sprint\Migration;


class Version201605210001 extends Version
{
    protected $description = "Англ. версия формы Регистрация на семинар";

    public function up()
    {
        if(!\CModule::IncludeModule("form")) {
            $this->outError("Отсутствует модуль Form");
        } else {
            if (!(new \CForm)->GetByID("SEMINAR_REG_EN", "Y")->GetNext()) {
                $newFormId = (new \CForm)->Copy(5);
                (new \CForm)->Set([
                    "NAME" => "Seminar registration",
                    "SID" => "SEMINAR_REG_EN",
                    "arSITE" => ["en"],
                    "arMENU" => array("en" => "Seminar registration"),
                ], $newFormId);
                $fieldsRes = \CFormField::GetList($newFormId, "", $by, $order, []);
                while (($field = $fieldsRes->GetNext())) {
                    switch ($field["TITLE"]) {
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
                        case "Компания":
                            $TITLE = "Company";
                            break;
                        case "Должность":
                            $TITLE = "Status";
                            break;
                        case "Удобная дата посещения":
                            $TITLE = "Visit date";
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
                "SID" => "SEMINAR_REG_RU"
            ], 5);
        }
    }
}
