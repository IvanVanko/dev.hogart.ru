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

        $res = (new \CIBlockElement)->GetList(Array(), $arFilter, false, Array(), $arSelect);

        while($ob = $res->GetNextElement()){
            $arFields = $ob->GetFields();
            var_dump($arFields);

            $code = \CUtil::translit(trim($arFields['NAME']), 'ru',
                array('change_case' => 'L', 'replace_space' => '-', 'replace_other' => '-'));

            //проверяем наличие элемента с таким же кодом
            $rsItems = (new \CIBlockElement)->GetList(array(), array(
                'IBLOCK_ID' => $arFields['IBLOCK_ID'],
                "SECTION_ID" => $arFields['IBLOCK_SECTION_ID'],
                "CODE" => $code
            ), false, false, array('ID', 'IBLOCK_SECTION_ID'));
            if ($rsItems->AffectedRowsCount()) {
                $code = $code . uniqid("_");
            }
            var_dump($code);
            $update = (new \CIBlockElement)->Update($arFields['ID'], array('CODE'=>$code));
            var_dump($update);
            exit();
        }
    }
}
