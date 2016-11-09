<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 06/10/2016
 * Time: 18:07
 */

namespace Hogart\Lk\Entity;


interface IOrderEventNote
{
    static function getOrderEventNote($entity_id, $event);
}