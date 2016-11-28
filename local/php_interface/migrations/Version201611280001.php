<?php
/**
 * Created by PhpStorm.
 * User: Ivan Kiselev
 * Date: 20/05/16
 * Time: 14:53
 */

namespace Sprint\Migration;


use Sprint\Migration\Helpers\IblockHelper;

class Version201611280001 extends Version
{
    protected $description = "Расширение свойств инфоблока для хранение доп. параметров";

    public function up()
    {
        $events = GetModuleEvents("iblock", "OnBeforeIBlockPropertyUpdate", true);
        foreach ($events as $iKey => $event) {
            if ($event["TO_MODULE_ID"] == "defa.tools") {
                RemoveEventHandler("iblock", "OnBeforeIBlockPropertyUpdate", $iKey);
            }
        }

        $iBlockHelper = new IblockHelper();

        if ($iBlockHelper->addPropertyIfNotExists(1, [
            "CODE" => "kit_count",
            "NAME" => "количество в упаковке (число)",
            "PROPERTY_TYPE" => "N",
            "FILTRABLE" => "N"
        ])) {
            $this->outSuccess("Добавлено свойство \"количество в упаковке (число)\" в инфоблок \"Каталог\"");
        }

        if ($iBlockHelper->addPropertyIfNotExists(1, [
            "CODE" => "kit_count_unit_messure_catalog",
            "NAME" => "единица измерения упаковочного количества",
            "PROPERTY_TYPE" => "N",
            "FILTRABLE" => "N"
        ])) {
            $this->outSuccess("Добавлено свойство \"единица измерения упаковочного количества\" в инфоблок \"Каталог\"");
        }
    }
}
