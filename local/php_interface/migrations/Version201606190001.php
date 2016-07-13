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
        \CModule::IncludeModule("catalog");

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