<?php

/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 20/04/16
 * Time: 14:24
 */
namespace Sprint\Migration;

class Version201604220001 extends Version
{
    protected $description = "Обновления для задач 34";

    public function up()
    {
        if(!\CModule::IncludeModule("form")) {
            $this->outError("Отсутствует модуль Form");
        } else {
            $res = \CFormField::GetList("9", "", $by, $order, [
                "TITLE" => "Компания"
            ]);
            while (($field = $res->GetNext())) {
                if (\CFormField::Set([
                    "REQUIRED" => "Y",
                    "FORM_ID" => $field["FORM_ID"]
                ], $field["ID"])) {
                    $this->outSuccess("Поле \"Компания\" формы \"Регистрация на акцию\" сделана обязательным");
                }
            }
        }
    }

    public function down(){
        return true;
    }
}