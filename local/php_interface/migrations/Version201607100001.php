<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 10/07/16
 * Time: 03:21
 */

namespace Sprint\Migration;


class Version201607100001 extends Version
{
    protected $description = "Обновление блока Реализованые проекты";

    public function up()
    {
        var_dump(\CUserOptions::GetOption("form", "form_element_18", []));
    }

    public function down()
    {
        return parent::down(); // TODO: Change the autogenerated stub
    }

}