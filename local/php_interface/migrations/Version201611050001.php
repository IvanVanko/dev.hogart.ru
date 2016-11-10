<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 05/11/2016
 * Time: 01:42
 */

namespace Sprint\Migration;


use Sprint\Migration\Helpers\IblockHelper;

class Version201611050001 extends Version
{
    protected $description = "Добавление свойств блока Каталога";

    public function up()
    {
        $iBlockHelper = new IblockHelper();
        if ($iBlockHelper->addPropertyIfNotExists(1, [
            "CODE" => "days_till_receive",
            "NAME" => "число дней доставки (число)",
            "PROPERTY_TYPE" => "N",
            "FILTRABLE" => "N"
        ])) {
            $this->outSuccess("Добавлено свойство \"число дней доставки (число)\" в инфоблок \"Каталог\"");
        }
        if ($iBlockHelper->addPropertyIfNotExists(1, [
            "CODE" => "warehouse",
            "NAME" => "признак складской позиции",
            "PROPERTY_TYPE" => "S",
            "USER_TYPE" => "Checkbox",
            "FILTRABLE" => "N",
            "SETTINGS" => [
                "DEFAULT_VALUE" => "N",
            ]
        ])) {
            $this->outSuccess("Добавлено свойство \"признак складской позиции\" в инфоблок \"Каталог\"");
        }
        return true;
    }

}