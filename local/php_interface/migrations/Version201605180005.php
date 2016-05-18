<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 18/05/16
 * Time: 16:50
 */

namespace Sprint\Migration;


class Version201605180005 extends Version
{
    protected $description = "Правка блока Helpful information";

    public function up()
    {
        $events = GetModuleEvents("iblock", "OnBeforeIBlockPropertyUpdate", true);
        foreach ($events as $iKey => $event) {
            if ($event["TO_MODULE_ID"] == "defa.tools") {
                RemoveEventHandler("iblock", "OnBeforeIBlockPropertyUpdate", $iKey);
            }
        }
        (new \CIBlock)->Update(41, [
            "DETAIL_PAGE_URL" => "#SITE_DIR#/helpful-information/#ELEMENT_CODE#/"
        ]);
    }
}
