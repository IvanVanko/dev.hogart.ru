<?php

IncludeModuleLangFile(__FILE__);

if ($APPLICATION->GetGroupRight("kontora.komments") >= "R") {
    $aMenu = array(
        "parent_menu" => "global_menu_services",
        "section"     => "comments",
        "sort"        => 550,
        "text"        => GetMessage("TEXT"),
        "title"       => GetMessage("TITLE"),
        "url"         => "comments_list.php",
        "icon"        => "blog_menu_icon",
        "page_icon"   => "blog_page_icon",
    );
    
    return $aMenu;
}

return false;
