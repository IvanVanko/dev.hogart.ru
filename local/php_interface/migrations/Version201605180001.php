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
            (new \CForm)->Set([
                "SID" => "MAKE_REQUEST_RU"
            ], 7);
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
    }
}
