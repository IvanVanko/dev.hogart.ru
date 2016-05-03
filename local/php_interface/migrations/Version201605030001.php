<?php

/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 20/04/16
 * Time: 14:24
 */
namespace Sprint\Migration;

use Sprint\Migration\Helpers\IblockHelper;

require_once $_SERVER["DOCUMENT_ROOT"] . "/k_1c_upload/ParsingModel.php";

class Version201605030001 extends Version
{
    protected $description = "Изменения инфоблоков по задаче #54";

    public function up()
    {
        $IblockHelper = new IblockHelper();

        if ($IblockHelper->addPropertyIfNotExists(\ParsingModel::COLLECTIONS_IBLOCK_ID, [
            "CODE" => "id_cat",
            "NAME" => "Категория коллекции",
            "PROPERTY_TYPE" => "G",
            "LINK_IBLOCK_ID" => \ParsingModel::CATALOG_IBLOCK_ID
        ])) {
            $this->outSuccess("Добавлено свойство \"Категория коллекции\" в Инфоблок \"Коллекции\"");
        }
    }

    public function down(){
        return true;
    }
}