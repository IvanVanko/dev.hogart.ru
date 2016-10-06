<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 12/09/16
 * Time: 18:03
 */

namespace Hogart\Lk\Helper\Template;


class Cart
{
    /** @var  string */
    private static $container;
    /** @var  string */
    private static $ajax_id;

    /**
     * @param \CBitrixComponent $component
     * @return bool|string
     */
    public static function init(\CBitrixComponent $component)
    {
        if (null === self::$ajax_id) {
            self::$ajax_id = Ajax::GetAjaxId($component);
        }
        return self::$ajax_id;
    }

    /**
     * @param $text
     * @param $params
     * @param $ext
     * @return string
     */
    public static function Link($text, $params, $ext)
    {
        return Ajax::Link($text, self::getContainer(), self::$ajax_id, $params, $ext);
    }

    /**
     * @return string
     */
    public static function getContainer()
    {
        if (null === self::$container) {
            self::$container = uniqid("cart-container-");
        }
        return self::$container;
    }
}