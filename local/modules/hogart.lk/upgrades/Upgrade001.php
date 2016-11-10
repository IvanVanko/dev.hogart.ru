<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 15/08/16
 * Time: 16:01
 */

namespace Hogart\Lk\Upgrade;


class Upgrade001 extends AbstractUpgrade
{
    public function doUpgrade()
    {
        CopyDirFiles(Module::getModuleDir() . "/install/components", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/components", true, true);
        $group = (new \CGroup())->GetListEx([], ["STRING_ID" => "HOGART_LK"])->Fetch();
        if (empty($group['ID'])) {
            $group['ID'] = (new \CGroup)->Add([
                "ACTIVE" => "Y",
                "NAME" => "Аккаунты клиентов",
                "DESCRIPTION" => "Пользователи с доступом к функциям Хогарт.ЛК",
                "STRING_ID" => "HOGART_LK"
            ]);
        }
    }
}