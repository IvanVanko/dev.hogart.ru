<?php
/**
 * Created by PhpStorm.
 * User: Ivan Kiselev
 * Date: 20/05/16
 * Time: 14:53
 */

namespace Sprint\Migration;


use Sprint\Migration\Helpers\IblockHelper;

class Version201607130001 extends Version
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
        $documentIblockId = \CIBlock::GetList(array('SORT' => 'ASC'), array('CHECK_PERMISSIONS' => 'N', '=NAME' => 'Документация'))->Fetch()['ID'];


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

        if ($iBlockHelper->addPropertyIfNotExists(1, [
            "CODE" => "advert",
            "NAME" => "Рекламные материалы",
            "PROPERTY_TYPE" => "E",
            "USER_TYPE" => "EAutocomplete",
            "LINK_IBLOCK_ID"=>$documentIblockId,
            "MULTIPLE"=>'Y',
            "FILTRABLE" => "N"
        ])) {
            $this->outSuccess("Добавлено свойство \"Рекламные материалы\" а инфоблок \"Каталог\"");
        }
    }
}
