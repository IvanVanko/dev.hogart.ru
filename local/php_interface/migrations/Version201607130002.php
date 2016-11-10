<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 20/05/16
 * Time: 14:53
 */

namespace Sprint\Migration;


class Version201607130002 extends Version
{
    protected $description = "Смена кодировки таблицы 'b_catalog_measure'";

    public function up()
    {
        global $DB;

        $DB->Query("ALTER TABLE b_catalog_measure CHARACTER SET utf8");
        $DB->Query("ALTER TABLE b_catalog_measure MODIFY `MEASURE_TITLE` VARCHAR(500) CHARACTER SET utf8");
        $DB->Query("ALTER TABLE b_catalog_measure MODIFY `SYMBOL_RUS` VARCHAR(20) CHARACTER SET utf8");
        $DB->Query("ALTER TABLE b_catalog_measure MODIFY `SYMBOL_INTL` VARCHAR(20) CHARACTER SET utf8");
        $DB->Query("ALTER TABLE b_catalog_measure MODIFY `SYMBOL_LETTER_INTL` VARCHAR(20) CHARACTER SET utf8");
    }
}
