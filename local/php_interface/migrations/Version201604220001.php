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

class Version201604220001 extends Version
{
    protected $description = "Обновления для задач 34";

    public function up()
    {
        $by = "s_id";
        $order = "asc";
        $res = \CFormField::GetList("9", "", $by, $order, [
            "TITLE" => "Компания"
        ]);
        while (($field = $res->GetNext())) {
            if (\CFormField::Set([
                "REQUIRED" => "Y"
            ], $field["ID"])) {
                $this->outSuccess("Поле \"Компания\" формы \"Регистрация на акцию\" сделана обязательным");
            }
        }
    }

    public function down(){
        return true;
    }
}