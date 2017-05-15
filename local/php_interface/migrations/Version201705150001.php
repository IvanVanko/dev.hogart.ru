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

class Version201705150001 extends Version
{
    public function up() {
        $userTypeEntityHelper = new UserTypeEntityHelper();
        $userTypeEntityHelper->addUserTypeEntityIfNotExists(
            "IBLOCK_" . CATALOG_IBLOCK_ID . "_SECTION",
            "UF_PRICE_LIST_COVER",
            [
                "USER_TYPE_ID" => "file",
                "EDIT_FORM_LABEL" => array('ru' => 'Обложка прайс-листа', 'en' => 'Price list cover'),
                "LIST_COLUMN_LABEL" => array('ru' => 'Обложка прайс-листа', 'en' => 'Price list cover'),
                "LIST_FILTER_LABEL" => array('ru' => 'Обложка прайс-листа', 'en' => 'Price list cover'),
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