<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 20/06/16
 * Time: 00:02
 */

namespace Sprint\Migration;


class Version201606190001 extends Version
{
    public function up()
    {
        global $DB;
        \CModule::IncludeModule("catalog");

        $DB->Query("ALTER TABLE b_catalog_group_lang CHARACTER SET utf8");
        $DB->Query("ALTER TABLE b_catalog_group_lang MODIFY `NAME` VARCHAR(100) CHARACTER SET utf8");
        
        \CCatalogGroup::Update(1, [
            "USER_LANG" => [
                "ru" => "Цена",
                "en" => "Price"
            ]
        ]);
    }

    public function down()
    {
    }

}