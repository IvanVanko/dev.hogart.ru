<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 25/05/16
 * Time: 19:09
 */

namespace Sprint\Migration;

use Sprint\Migration\Helpers\UserTypeEntityHelper;
use CUSTOM\Entity\ProductItemMeasureTable;

class Version201607220001 extends Version
{
    protected $description = "Добавление нового HL-блока с единицами измерений продукта";
    public function up()
    {

        global $DB;
        $DB->Query("ALTER TABLE product_item_measure CHARACTER SET utf8");
        $DB->Query("ALTER TABLE product_item_measure MODIFY `UF_MESSURE_NAME` VARCHAR(20) CHARACTER SET utf8");
        $DB->Query("ALTER TABLE product_item_measure MODIFY `UF_MESSURE_ID` VARCHAR(3) CHARACTER SET utf8");
    }
}
