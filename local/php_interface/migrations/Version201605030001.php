<?php

/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 20/04/16
 * Time: 14:24
 */
namespace Sprint\Migration;

use Sprint\Migration\Helpers\IblockHelper;
use Sprint\Migration\Helpers\UserTypeEntityHelper;

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

        $userTypeEntityHelper = new UserTypeEntityHelper();
        $userTypeEntityHelper->addUserTypeEntityIfNotExists(
            "IBLOCK_" . \ParsingModel::CATALOG_IBLOCK_ID . "_SECTION",
            "UF_SECTION_VIEW",
            [
                "USER_TYPE_ID" => "integer",
                "EDIT_FORM_LABEL" => array('ru' => 'Вид группы', 'en' => 'Section view'),
                "LIST_COLUMN_LABEL" => array('ru' => 'Вид группы', 'en' => 'Section view'),
                "LIST_FILTER_LABEL" => array('ru' => 'Вид группы', 'en' => 'Section view'),
                "HELP_MESSAGE" => array(
                    "ru" => "отображение по умолчанию (1 - список, 2 - плитка, 3 - таблица)"
                ),
                "SETTINGS" => [
                    "DEFAULT_VALUE" => 1,
                    "MIN_VALUE" => 1,
                    "MAX_VALUE" => 3
                ]
            ]
        );
    }

    public function down(){
        return true;
    }
}