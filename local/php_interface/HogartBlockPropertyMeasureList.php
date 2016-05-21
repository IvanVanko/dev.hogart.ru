<?php

/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 20/05/16
 * Time: 15:36
 */
class HogartBlockPropertyMeasureList
{
    public function GetUserTypeDescription()
    {
        return array(
            "PROPERTY_TYPE"      => "L",
            "USER_TYPE"         => "LMeasure",
            "DESCRIPTION"      => "Привязка к ед. измерения",
            "GetPropertyFieldHtml" => array("HogartBlockPropertyMeasureList","GetPropertyFieldHtml"),
        );
    }

    public function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName)
    {
        
    }
}

AddEventHandler("iblock", "OnIBlockPropertyBuildList", array("HogartBlockPropertyMeasureList", "GetUserTypeDescription"));