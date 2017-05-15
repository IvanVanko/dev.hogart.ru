<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 13/05/2017
 * Time: 20:57
 */

namespace Sprint\Migration;


use Sprint\Migration\Helpers\IblockHelper;
use Sprint\Migration\Helpers\UserTypeEntityHelper;

class Version201705130001 extends Version
{
    public function up(){

        $IblockHelper = new IblockHelper();

        $blocks = [3, 6];
        foreach ($blocks as $blockId) {
            $block = \CIBlock::GetList(array('SORT' => 'ASC'), array('CHECK_PERMISSIONS' => 'N', '=ID' => $blockId))->Fetch();
            if ($IblockHelper->addPropertyIfNotExists($blockId, [
                "CODE" => "INDEX_SHOW",
                "NAME" => "Показывать на главной",
                "ACTIVE" => "Y",
                "PROPERTY_TYPE" => "S",
                "USER_TYPE" => "Checkbox",
                "FILTRABLE" => "Y",
                "IS_REQUIRED" => "N"
            ])) {
                $this->outSuccess("Добавлено свойство \"Показывать на главной\" в Инфоблок \"{$block["NAME"]}\"");
            }
        }

        if ($IblockHelper->addPropertyIfNotExists(BRAND_IBLOCK_ID, [
            "CODE" => "INDEX_LOGO",
            "NAME" => "Логотип на главной",
            "ACTIVE" => "Y",
            "WITH_DESCRIPTION" => "Y",
            "PROPERTY_TYPE" => "F",
            "FILE_TYPE" => "jpg, gif, png, jpeg, svg",
            "IS_REQUIRED" => "Y"
        ])) {
            $this->outSuccess("Добавлено свойство \"Иконка на главной\" в Инфоблок \"Бренды\"");
        }

        $userTypeEntityHelper = new UserTypeEntityHelper();
        $userTypeEntityHelper->addUserTypeEntityIfNotExists(
            "IBLOCK_" . CATALOG_IBLOCK_ID . "_SECTION",
            "UF_ICON",
            [
                "USER_TYPE_ID" => "file",
                "EDIT_FORM_LABEL" => array('ru' => 'Иконка группы', 'en' => 'Section icon'),
                "LIST_COLUMN_LABEL" => array('ru' => 'Иконка группы', 'en' => 'Section icon'),
                "LIST_FILTER_LABEL" => array('ru' => 'Иконка группы', 'en' => 'Section icon'),
                "SETTINGS" => [
                    "SETTINGS" => "jpg,gif,png,jpeg,svg"
                ]
            ]
        );
    }

    public function down(){
        return true;
    }
}