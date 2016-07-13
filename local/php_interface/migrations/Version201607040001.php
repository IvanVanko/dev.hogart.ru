<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 18/05/16
 * Time: 16:50
 */

namespace Sprint\Migration;
use Sprint\Migration\Helpers\IblockHelper;



class Version201607040001 extends Version
{
    protected $description = "Обновление Почтового шаблона для Формы расшарить по email";

    public function up()
    {
        $block_id = CATALOG_IBLOCK_ID;
        $arSelect = Array("ID", "IBLOCK_ID", "NAME", "IBLOCK_SECTION_ID", 'CODE');
        $arFilter = Array("IBLOCK_ID"=>intval($block_id), "CODE"=>false);

        $res = (new \CIBlockElement)->GetList(Array(), $arFilter, false, false, $arSelect);
        while($ob = $res->GetNextElement()){
            $arFields = $ob->GetFields();
            if($arFields['CODE'] != '')
                continue;
            $update = (new \CIBlockElement)->Update($arFields['ID'], array('NAME'=>$arFields['NAME']), false, true);
        }
    }
}
