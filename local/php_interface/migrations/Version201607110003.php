<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 11/07/16
 * Time: 00:51
 */

namespace Sprint\Migration;


use Sprint\Migration\Helpers\IblockHelper;

class Version201607110003 extends Version
{
    protected $description = "Задача HG-34: работа с блоком Семинары";

    public function up()
    {
        $blockId = 8;
        $iBlockHelper = new IblockHelper();
        $iBlockHelper->updatePropertyIfExists($blockId, "sem_start_date", array_merge($iBlockHelper->getProperty($blockId, "sem_start_date"), [
            "IS_REQUIRED" => "Y"
        ]));

        $iBlockHelper->updatePropertyIfExists($blockId, "sem_end_date", array_merge($iBlockHelper->getProperty($blockId, "sem_end_date"), [
            "IS_REQUIRED" => "Y"
        ]));

        \CUserOptions::DeleteOptionsByName("form", "form_element_" . $blockId);
        \CUserOptions::SetOption("form", "form_element_" . $blockId, [
            "tabs" => "edit1--#--Семинар--,--ACTIVE--#--Активность--,--ACTIVE_FROM--#--Начало активности--,--ACTIVE_TO--#--Окончание активности--,--NAME--#--*Название--,--CODE--#--*Символьный код--,--SORT--#--Сортировка--,--edit1_csection1--#----Дата и место--,--PROPERTY_" . $iBlockHelper->getPropertyId($blockId, "sem_start_date") . "--#--Дата начала семинара--,--PROPERTY_" . $iBlockHelper->getPropertyId($blockId, "sem_end_date") . "--#--Дата конца семинара--,--PROPERTY_" . $iBlockHelper->getPropertyId($blockId, "time") . "--#--Время начала--,--PROPERTY_" . $iBlockHelper->getPropertyId($blockId, "end_time") . "--#--Время завершения--,--PROPERTY_" . $iBlockHelper->getPropertyId($blockId, "address") . "--#--Адрес офиса--,--PROPERTY_" . $iBlockHelper->getPropertyId($blockId, "map") . "--#--Карта--,--PROPERTY_" . $iBlockHelper->getPropertyId($blockId, "public_transport") . "--#--Проезд общественным транспортом--,--IBLOCK_ELEMENT_PROP_VALUE--#----Значения свойств--,--PROPERTY_" . $iBlockHelper->getPropertyId($blockId, "direction") . "--#--Направление--,--PROPERTY_" . $iBlockHelper->getPropertyId($blockId, "org") . "--#--Организатор--,--PROPERTY_" . $iBlockHelper->getPropertyId($blockId, "lecturer") . "--#--Лекторы--,--PROPERTY_" . $iBlockHelper->getPropertyId($blockId, "calendar") . "--#--Выводить в календаре на главной--,--PROPERTY_" . $iBlockHelper->getPropertyId($blockId, "brand") . "--#--Бренд--,--PROPERTY_" . $iBlockHelper->getPropertyId($blockId, "program_txt") . "--#--Текст программы--;--edit5--#--Анонс--,--PREVIEW_PICTURE--#--Картинка для анонса--,--PREVIEW_TEXT--#--Описание для анонса--;--edit6--#--Подробно--,--DETAIL_PICTURE--#--Детальная картинка--,--DETAIL_TEXT--#--Детальное описание--;--cedit1--#--Отзыв--,--PROPERTY_" . $iBlockHelper->getPropertyId($blockId, "review_author_company") . "--#--Компания автора отзыва--,--PROPERTY_" . $iBlockHelper->getPropertyId($blockId, "review_author") . "--#--Автор отзыва--,--PROPERTY_" . $iBlockHelper->getPropertyId($blockId, "review") . "--#--Отзыв--;--cedit2--#--Материалы--,--PROPERTY_" . $iBlockHelper->getPropertyId($blockId, "materials") . "--#--Материалы семинара--;--"
        ], true);
    }

    public function down()
    {
        return parent::down(); // TODO: Change the autogenerated stub
    }

}