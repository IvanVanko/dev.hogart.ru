<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 29/09/2016
 * Time: 21:23
 */

namespace Hogart\Lk\Helper\Template;


class Money
{
    public static function show($number)
    {
        return number_format($number,  2, '.', ' ');
    }
}