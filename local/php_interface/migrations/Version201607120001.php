<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 12/07/16
 * Time: 14:32
 */

namespace Sprint\Migration;


class Version201607120001 extends Version
{
    protected $description = "Переименование формы Предложить свою тему семинара";

    public function up()
    {
        if(!\CModule::IncludeModule("form")) {
            $this->outError("Отсутствует модуль Form");
        } else {
            $form = (new \CForm);
            $formElement = $form->GetByID("OFFER_YOUR_SEMINAR_TOPIC_RU", "Y")->Fetch();
            $form->Set([
                "NAME" => "Предложить тему",
                "SID" => "OFFER_YOUR_SEMINAR_TOPIC_RU",
                "arMENU" => array("ru" => "Предложить тему"),
            ], $formElement["ID"]);
        }
    }
}
