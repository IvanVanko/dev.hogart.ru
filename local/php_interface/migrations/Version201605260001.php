<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 18/05/16
 * Time: 16:50
 */

namespace Sprint\Migration;


class Version201605260001 extends Version
{
    protected $description = "Англ. версия формы Поделиться по e-mail";

    public function up()
    {
        if(!\CModule::IncludeModule("form")) {
            $this->outError("Отсутствует модуль Form");
        } else {
            if (!(new \CForm)->GetByID("SHARE_EMAIL_EN", "Y")->GetNext()) {
                $newFormId = (new \CForm)->Copy(10);
                (new \CForm)->Set([
                    "NAME" => "Share via E-mail",
                    "SID" => "SHARE_EMAIL_EN",
                    "arSITE" => ["en"],
                    "arMENU" => array("en" => "Share via E-mail"),
                ], $newFormId);
                $fieldsRes = \CFormField::GetList($newFormId, "", $by, $order, []);
                while (($field = $fieldsRes->GetNext())) {
                    switch ($field["TITLE"]) {
                        case "Материалы страницы":
                            $TITLE = "Materials";
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
                "SID" => "SHARE_EMAIL_RU"
            ], 10);
        }
    }
}
