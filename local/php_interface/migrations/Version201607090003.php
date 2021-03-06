<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 09/07/16
 * Time: 17:22
 */

namespace Sprint\Migration;


use Sprint\Migration\Helpers\IblockHelper;

class Version201607090003 extends Version
{
    protected $description = "Обновление инфо-блока Компания HG-32 #2";

    public function up()
    {
        $iBlockHelper = new IblockHelper();
        $iBlockHelper->deletePropertyIfExists(5, "video_file");
        $iBlockHelper->deletePropertyIfExists(5, "video_link");
    }

    public function down()
    {
        return parent::down(); // TODO: Change the autogenerated stub
    }
}