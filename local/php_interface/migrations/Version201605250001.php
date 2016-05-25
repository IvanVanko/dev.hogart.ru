<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 25/05/16
 * Time: 19:09
 */

namespace Sprint\Migration;

use Bitrix\Main\Entity;
use Sprint\Migration\Helpers\UserTypeEntityHelper;

class Version201605250001 extends Version
{
    protected $description = "Добавление полей в HL-блок SectionPropertySort";
    public function up()
    {
        $UserTypeEntityHelper = new UserTypeEntityHelper();
        $UserTypeEntityHelper->addUserTypeEntityIfNotExists("SectionPropertySort", "UF_MAIN_TABLE", [
            "USER_TYPE_ID" => "boolean"
        ]);
        $UserTypeEntityHelper->addUserTypeEntityIfNotExists("SectionPropertySort", "UF_SORT_TABLE", [
            "USER_TYPE_ID" => "boolean"
        ]);
    }
}
