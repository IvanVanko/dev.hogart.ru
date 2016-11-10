<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 27/07/16
 * Time: 18:48
 */
global $APPLICATION;

if ($APPLICATION->GetGroupRight("hogart.lk") != "D") {
    $aMenu = array(
        "parent_menu" => "global_menu_services",
        "section" => "Личный кабинет партнеров компании Хогарт",
        "sort" => 50,
        "text" => "Личный кабинет Хогарт",
        "icon" => "blog_menu_icon",
        "page_icon" => "blog_page_icon",
        "items_id" => "hogart_lk",
        "items" => array(
            array(
                "text" => "Настройки",
                "url" => "hogart_lk.php?lang=" . LANGUAGE_ID,
            ),
        )
    );

    return $aMenu;
}

return false;
