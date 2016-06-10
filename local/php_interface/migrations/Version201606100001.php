<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 18/05/16
 * Time: 16:50
 */

namespace Sprint\Migration;


class Version201606100001 extends Version
{
    public function up()
    {
        if(!\CModule::IncludeModule("form")) {
            $this->outError("Отсутствует модуль Form");
        } else {

            $arFilter = [
                "SID" => 'MAKE_REQUEST_RU|MAKE_REQUEST_EN',// 'MAKE_REQUEST_EN'],
                "SID_EXACT_MATCH" => 'Y',
            ];
            $rsForms = (new \CForm)->GetList($by="s_id", $order="desc", $arFilter, $is_filtered);
            while ($arForm = $rsForms->Fetch())
            {
                (new \CForm)->Set([
                    "USE_CAPTCHA" => "Y",
                ], $arForm['ID']);
            }
        }
    }
}
