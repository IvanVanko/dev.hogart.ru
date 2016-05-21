<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 20/05/16
 * Time: 14:53
 */

namespace Sprint\Migration;


use Sprint\Migration\Helpers\IblockHelper;

class Version201605210002 extends Version
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
            "NAME" => "Упаковочное количество",
            "PROPERTY_TYPE" => "N",
            "FILTRABLE" => "N"
        ])) {
            $this->outSuccess("Добавлено свойство \"Упаковочное количество\" а инфоблок \"Каталог\"");
        }
        if ($iBlockHelper->addPropertyIfNotExists(1, [
            "CODE" => "kit_count_unitmessure_id",
            "NAME" => "Единица измерения упаковочного количества",
            "PROPERTY_TYPE" => "L",
            "USER_TYPE" => "LMeasure",
            "FILTRABLE" => "Y"
        ])) {
            $this->outSuccess("Добавлено свойство \"Единица измерения упаковочного количества\" а инфоблок \"Каталог\"");
        }
        if ($iBlockHelper->addPropertyIfNotExists(1, [
            "CODE" => "default_count",
            "NAME" => "Кратность отгрузки (число)",
            "PROPERTY_TYPE" => "N",
            "FILTRABLE" => "N"
        ])) {
            $this->outSuccess("Добавлено свойство \"Кратность отгрузки (число)\" а инфоблок \"Каталог\"");
        }
        if ($iBlockHelper->addPropertyIfNotExists(1, [
            "CODE" => "date_added",
            "NAME" => "Дата добавления товара",
            "PROPERTY_TYPE" => "S",
            "USER_TYPE" => "DateTime",
            "FILTRABLE" => "N",
        ])) {
            $this->outSuccess("Добавлено свойство \"Дата добавления товара\" а инфоблок \"Каталог\"");
        }
        if ($iBlockHelper->addPropertyIfNotExists(1, [
            "CODE" => "date_changed",
            "NAME" => "Дата изменения данных о товаре",
            "PROPERTY_TYPE" => "S",
            "USER_TYPE" => "DateTime",
            "FILTRABLE" => "N"
        ])) {
            $this->outSuccess("Добавлено свойство \"Дата изменения данных о товаре\" а инфоблок \"Каталог\"");
        }
    }
}
