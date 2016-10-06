<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 23/09/2016
 * Time: 16:37
 */

namespace Hogart\Lk\Helper\Template;


interface IMessage
{
    function getMessage();
    function getSeverity();
    function getUnique();
}