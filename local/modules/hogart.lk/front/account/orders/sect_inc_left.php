<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 07/09/16
 * Time: 15:02
 *
 * @global CUser $USER
 * @global CMain $APPLICATION
 *
 */
if ($USER->IsAuthorized()) {
    $APPLICATION->sDirPath = str_replace($_SERVER["DOCUMENT_ROOT"], "", __DIR__);
    $APPLICATION->IncludeComponent("bitrix:menu", "hogart.lk.left", Array(
            "ROOT_MENU_TYPE" => "left",
            "MAX_LEVEL" => "1",
            "CHILD_MENU_TYPE" => "left",
            "USE_EXT" => "Y",
            "DELAY" => "N",
            "ALLOW_MULTI_SELECT" => "Y",
            "MENU_CACHE_TYPE" => "N",
            "MENU_CACHE_TIME" => "3600",
            "MENU_CACHE_USE_GROUPS" => "Y",
            "MENU_CACHE_GET_VARS" => ""
        )
    );
}
