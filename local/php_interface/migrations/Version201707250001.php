<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 25/07/16
 * Time: 14:33
 */

namespace Sprint\Migration;


class Version201707250001 extends Version
{
    protected $description = "Обновление разделов, присваивание GUID из 1C";
    
    public function up()
    {
        $branches = [
            "otoplenie" => "c87986bc-fb4d-4bd9-ade5-fa718e845956",
            "santekhnika" => "ec283c4c-179f-4223-b562-5826574692bb",
            "ventilyatsiya" => "e36acf8f-650e-4f9e-be5e-a8eef62f365a"
        ];
        $sectionClass = new \CIBlockSection();
        $sectionRes = $sectionClass->GetList([], ["CODE" => array_keys($branches)], false, ["ID", "CODE", "NAME"]);
        while ($section = $sectionRes->Fetch()) {
            if ($sectionClass->Update($section["ID"], [
                "XML_ID" => $branches[$section["CODE"]]
            ])) {
                $this->outSuccess("Разделу {$section["NAME"]} назначен XML_ID ({$branches[$section["CODE"]]})");
            }
        }
    }
}