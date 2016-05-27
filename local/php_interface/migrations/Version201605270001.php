<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 18/05/16
 * Time: 16:50
 */

namespace Sprint\Migration;


use Sprint\Migration\Helpers\IblockHelper;

class Version201605270001 extends Version
{
    protected $description = "Обновление инфоблока Документация";

    public function up()
    {
        if(!\CModule::IncludeModule("iblock")) {
            $this->outError("Отсутствует модуль Iblock");
        } else {
            $iBlockHelper = new IblockHelper();
            if ($iBlockHelper->addPropertyIfNotExists(10, [
                "NAME" => "Показывать в объекте",
                "CODE" => "show_in_object",
                "PROPERTY_TYPE" => "S",
                "USER_TYPE" => "Checkbox"
            ])) {
                $this->outSuccess("Добавлено свойство Показывать в объекте");
            }
        }
    }
}
