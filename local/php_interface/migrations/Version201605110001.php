<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 11/05/16
 * Time: 15:58
 */

namespace Sprint\Migration;


use Sprint\Migration\Helpers\IblockHelper;

class Version201605110001 extends Version
{
    public function up()
    {
        $iBlockHelper = new IblockHelper();
        if ($iBlockHelper->updatePropertyIfExists(10, 'type', [
            "IBLOCK_ID" => 10,
            "CODE" => "type",
            "VALUES" => [
                7 => [
                    "XML_ID" => "4e1de85f-fdf0-11e4-9045-003048b99ee9",
                    "VALUE" => "Буклеты"
                ],
                8 => [
                    "XML_ID" => "4e1de863-fdf0-11e4-9045-003048b99ee9",
                    "VALUE" => "Инструкция по монтажу"
                ],
                9 => [
                    "XML_ID" => "4e1de865-fdf0-11e4-9045-003048b99ee9",
                    "VALUE" => "Гарантийный талон/Тех. паспорт"
                ],
                10 => [
                    "XML_ID" => "4e1de85e-fdf0-11e4-9045-003048b99ee9",
                    "VALUE" => "Каталог"
                ],
                11 => [
                    "XML_ID" => "4e1de860-fdf0-11e4-9045-003048b99ee9",
                    "VALUE" => "Сертификаты"
                ],
                18 => [
                    "XML_ID" => "3ac566b4-e78f-11e4-9045-003048b99ee9",
                    "VALUE" => "Прайс-лист"
                ],
                97719 => [
                    "XML_ID" => "4e1de864-fdf0-11e4-9045-003048b99ee9",
                    "VALUE" => "Инструкция по эксплуатации"
                ],
            ]
        ])) {
            $this->outSuccess("Обновлены значения свойства \"Тип\" в Инфоблоке \"Документация\"");
        }
    }
}
