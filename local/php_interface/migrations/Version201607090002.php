<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 09/07/16
 * Time: 13:37
 */

namespace Sprint\Migration;


use Sprint\Migration\Helpers\IblockHelper;

class Version201607090002 extends Version
{
    protected $description = "Обновление инфо-блока Компания";

    public function up()
    {
        $iBlockHelper = new IblockHelper();
        $iBlockHelper->addPropertyIfNotExists(5, [
            "NAME" => "Преимущества компании",
            "CODE" => "infographics",
            "MULTIPLE" => "Y",
            "WITH_DESCRIPTION" => "Y",
            "PROPERTY_TYPE" => "F",
            "FILE_TYPE" => "jpg, gif, bmp, png, jpeg"
        ]);
    }

    public function down()
    {
        return parent::down(); // TODO: Change the autogenerated stub
    }


}