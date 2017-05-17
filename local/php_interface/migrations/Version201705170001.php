<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 13/05/2017
 * Time: 20:57
 */

namespace Sprint\Migration;


use Sprint\Migration\Helpers\IblockHelper;
use Sprint\Migration\Helpers\UserTypeEntityHelper;

class Version201705170001 extends Version
{
    public function up() {
        $IblockHelper = new IblockHelper();

        if ($IblockHelper->addPropertyIfNotExists(BRAND_IBLOCK_ID, [
            "CODE" => "BRANCH",
            "NAME" => "Направления",
            "ACTIVE" => "Y",
            "PROPERTY_TYPE" => "G",
            "MULTIPLE" => "Y",
            "MULTIPLE_CNT" => 5,
            "LINK_IBLOCK_ID" => CATALOG_IBLOCK_ID
        ])) {
            $this->outSuccess("Добавлено свойство \"Направления\" в Инфоблок \"Бренды\"");
        }
    }

    public function down(){
        return true;
    }
}