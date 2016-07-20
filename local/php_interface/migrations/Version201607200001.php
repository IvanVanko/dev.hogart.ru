<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 20/07/16
 * Time: 10:28
 */

namespace Sprint\Migration;


use Sprint\Migration\Helpers\IblockHelper;

class Version201607200001 extends Version
{
    protected $description = "Возможность добавлять описание в фотогалереях блоков";

    public function up()
    {
        $events = GetModuleEvents("iblock", "OnBeforeIBlockPropertyUpdate", true);
        foreach ($events as $iKey => $event) {
            if ($event["TO_MODULE_ID"] == "defa.tools") {
                RemoveEventHandler("iblock", "OnBeforeIBlockPropertyUpdate", $iKey);
            }
        }
        
        $iBlockHelper = new IblockHelper();
        $iBlockHelper->updatePropertyIfExists($iBlockHelper->getIblockId("references_s1"), "photogallery", [
            "WITH_DESCRIPTION" => "Y"
        ]);

        $iBlockHelper->updatePropertyIfExists($iBlockHelper->getIblockId("brands_s1"), "photogallery_about_company", [
            "WITH_DESCRIPTION" => "Y"
        ]);

        $iBlockHelper->updatePropertyIfExists($iBlockHelper->getIblockId("brands_s1"), "photogallery_about_products", [
            "WITH_DESCRIPTION" => "Y"
        ]);
    }
}