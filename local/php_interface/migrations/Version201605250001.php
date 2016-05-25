<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 25/05/16
 * Time: 19:09
 */

namespace Sprint\Migration;

use Bitrix\Main\Entity;

class Version201605250001 extends Version
{
    protected $description = "Добавление полей в HL-блок SectionPropertySort";
    public function up()
    {
        $entity = \CHLEntity::GetEntityByName('SectionPropertySort');
        if (!$entity->hasField("UF_MAIN_TABLE")) {
            $entity->addField(new Entity\BooleanField("UF_MAIN_TABLE"), ["default_value" => false]);
        }
        if (!$entity->hasField("UF_SORT_TABLE")) {
            $entity->addField(new Entity\BooleanField("UF_SORT_TABLE"), ["default_value" => false]);
        }
    }
}
