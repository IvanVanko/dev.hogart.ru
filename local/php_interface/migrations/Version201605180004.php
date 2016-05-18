<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 18/05/16
 * Time: 16:50
 */

namespace Sprint\Migration;


use Sprint\Migration\Helpers\IblockHelper;

class Version201605180004 extends Version
{
    protected $description = "Правка блока Seminars";

    public function up()
    {
        $iBlockHelper = new IblockHelper();
        $iBlockHelper->updatePropertyIfExists(39, "materials", [
            "NAME" => "Seminar presentations"
        ]);
    }
}
