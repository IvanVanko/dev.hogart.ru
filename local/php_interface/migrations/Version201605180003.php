<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 18/05/16
 * Time: 16:50
 */

namespace Sprint\Migration;


class Version201605180003 extends Version
{
    protected $description = "Англ. версия формы \"ПРЕДЛОЖИТЕ СВОЮ ТЕМУ СЕМИНАРА\"";

    public function up()
    {
        if(!\CModule::IncludeModule("form")) {
            $this->outError("Отсутствует модуль Form");
        } else {
            if (!(new \CForm)->GetByID("OFFER_YOUR_SEMINAR_TOPIC_EN", "Y")->GetNext()) {
                $newFormId = (new \CForm)->Copy(4);
                (new \CForm)->Set([
                    "NAME" => "Offer your seminar topic",
                    "SID" => "OFFER_YOUR_SEMINAR_TOPIC_EN",
                    "arSITE" => ["en"],
                    "arMENU" => array("en" => "Offer your seminar topic"),
                ], $newFormId);
                $fieldsRes = \CFormField::GetList($newFormId, "", $by, $order, []);
                while (($field = $fieldsRes->GetNext())) {
                    switch ($field["TITLE"]) {
                        case "Тема":
                            $TITLE = "Subject";
                            break;
                        case "Компания":
                            $TITLE = "Company";
                            break;
                        case "Планируемое число участвников":
                            $TITLE = "Participants number";
                            break;
                        case "Комментарий":
                            $TITLE = "Comments";
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
                "SID" => "OFFER_YOUR_SEMINAR_TOPIC_RU"
            ], 4);
            \CFormField::Set([
                "FORM_ID" => 4,
                "TITLE" => "Планируемое число участников"
            ], 17);
        }
    }
}
