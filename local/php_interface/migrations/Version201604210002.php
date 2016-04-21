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

class Version201604210002 extends Version
{
    protected $description = "Обновления для задач 46 #2";

    public function up(){

        $IblockHelper = new IblockHelper();

        if ($IblockHelper->addPropertyIfNotExists(26, [
            "CODE" => "BARCODE",
            "NAME" => "Код для баркода",
            "ACTIVE" => "Y",
            "PROPERTY_TYPE" => "S",
            "FILTRABLE" => "Y",
            "IS_REQUIRED" => "Y"
        ])) {
            $this->outSuccess("Добавлено свойство \"Код для баркода\" в Инфоблок \"Регистрации на мероприятия\"");
        }
    }

    public function down(){
        return true;
    }
}