<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 03/10/2016
 * Time: 04:18
 */

namespace Hogart\Lk\Helper\Template;


class Text
{
    public static function ucfirst($text)
    {
        $text = explode(" ", $text);
        $text[0] = mb_convert_case($text[0], MB_CASE_TITLE, "UTF-8");
        return implode(" ", $text);
    }
}