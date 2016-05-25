<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 25/05/16
 * Time: 19:09
 */

namespace Sprint\Migration;

use Sprint\Migration\Helpers\UserTypeEntityHelper;

class Version201605250001 extends Version
{
    protected $description = "Добавление полей в HL-блок SectionPropertySort";
    public function up()
    {
        $UserTypeEntityHelper = new UserTypeEntityHelper();
        $entityId = "HLBLOCK_" . \CHLEntity::GetEntityIdByName('SectionPropertySort');
        $UserTypeEntityHelper->addUserTypeEntityIfNotExists($entityId, "UF_MAIN_TABLE", [
            "USER_TYPE_ID" => "boolean"
        ]);
        $UserTypeEntityHelper->addUserTypeEntityIfNotExists($entityId, "UF_SORT_TABLE", [
            "USER_TYPE_ID" => "boolean"
        ]);

        \CUserTypeEntity::Update($UserTypeEntityHelper->getUserTypeEntity($entityId, "UF_SECTION_ID")["ID"], [
            "SHOW_FILTER" => 'I'
        ]);
    }
}
