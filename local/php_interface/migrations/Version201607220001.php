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

//      Примеры использования
//            $item_measures = \CUSTOM\Entity\ProductItemMeasureTable::GetList(array(
//                'select' => array('*'),
//                'filter' => array('UF_ITEM_ID' => 1)
//            ))->fetch();

//        $entity = \CUSTOM\Entity\ProductItemMeasureTable::UpdateMeasures(1,
//            array(
//                "UF_ITEM_ID" => 1,
//                "UF_IS_MAIN" => 'Y',
//                "UF_XML_ID" => '16c83c34-ab4e-11e3-a5f1-003048b99ee9',
//                "UF_KOEF" => '3',
//                "UF_MESSURE_ID" => '796',
//                "UF_MESSURE_NAME" => 'шт',
//            )
//        );
//
//        $item_measures = ProductItemMeasureTable::GetListByItem(1);
//        while($m = $item_measures->fetch()){
//            var_dump($m);
//        }


//        var_dump($item_measures);


//        $entity = \CUSTOM\Entity\ProductItemMeasureTable::Add(
//            array(
//                "UF_ITEM_ID" => 1,
//                "UF_IS_MAIN" => 'Y',
//                "UF_XML_ID" => '16c83c34-ab4e-11e3-a5f1-003048b99ee9',
//                "UF_KOEF" => '1',
//                "UF_MESSURE_ID" => '796 ',
//                "UF_MESSURE_NAME" => 'шт',
//            )
//        );
//        $entity = \CUSTOM\Entity\ProductItemMeasureTable::Add(
//            array(
//                "UF_ITEM_ID" => 1,
//                "UF_IS_MAIN" => 'Y',
//                "UF_XML_ID" => '75619682-2e0c-11e6-a5ec-003048b99ee9',
//                "UF_KOEF" => '1',
//                "UF_MESSURE_ID" => '055 ',
//                "UF_MESSURE_NAME" => 'м2',
//            )
//        );
//        var_dump($entity);
        exit();
//        $UserTypeEntityHelper = new UserTypeEntityHelper();
//        $entityId = "HLBLOCK_" . \CHLEntity::GetEntityIdByName('SectionPropertySort');
//        $UserTypeEntityHelper->addUserTypeEntityIfNotExists($entityId, "UF_MAIN_TABLE", [
//            "USER_TYPE_ID" => "boolean"
//        ]);
//        $UserTypeEntityHelper->addUserTypeEntityIfNotExists($entityId, "UF_SORT_TABLE", [
//            "USER_TYPE_ID" => "boolean"
//        ]);
//
//        (new \CUserTypeEntity())->Update($UserTypeEntityHelper->getUserTypeEntity($entityId, "UF_SECTION_ID")["ID"], [
//            "SHOW_FILTER" => 'I'
//        ]);
    }
}
