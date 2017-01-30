<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 23/08/16
 * Time: 12:44
 */

namespace Hogart\Lk\Helper\Template;


use Bitrix\Main\Page\Asset;

class Dialog
{
    /** @var string */
    public static $id;
    /** @var array  */
    protected static $events = [];

    public static function Start($id, $options = [], $echo = true)
    {
        Asset::getInstance()->addJs('/local/modules/hogart.lk/assets/remodal/remodal.min.js');
        Asset::getInstance()->addCss('/local/modules/hogart.lk/assets/remodal/remodal.css');
        Asset::getInstance()->addCss('/local/modules/hogart.lk/assets/remodal/remodal-default-theme.css');

        if (null !== self::$id) {
            throw new \RuntimeException("Не закончен предыдущий диалог");
        }
        
        self::$id = $id;

        $dialog_options = !empty($options['dialog-options']) ? $options['dialog-options'] : "";
        $html =<<<HTML
        
<div class="remodal" data-remodal-id="$id" data-remodal-options="$dialog_options">
    <button data-remodal-action="close" class="remodal-close"></button>
HTML;
        if (!empty($options['title'])) {
            $html .= '<h3>' . $options['title'] . '</h3>';
        }
        if ($echo)
            echo $html;

        return $html;
    }

    public static function End($options = [], $echo = true)
    {
        $html = "";
        if (false !== $options['end_breaks']) {
            $html .= '<br><br>';
        }
        if (false !== $options['cancel']) {
            $html .= '<button data-remodal-action="cancel" class="btn btn-danger remodal-cancel">' . ($options['cancel_text'] ? : 'Отменить') . '</button>';
        }
        
        if (false !== $options['confirm']) {
            $html .= '<button data-remodal-action="confirm" class="btn btn-primary remodal-confirm">' . ($options['confirm_text'] ? : 'ОК') . '</button>';
        }
        
        $html .= '</div>';

        if (!empty(self::$events[self::$id])) {
            $html .= "<script language='JavaScript'>";
            foreach (self::$events[self::$id] as $event => $handlers) {
                $html .= implode(";", $handlers);
            }
            $html .= "</script>";
        }
        
        if ($echo)
            echo $html;

        self::$id = null;
        return $html;
    }

    public static function Event($event, $handler)
    {
        $id = self::$id;
        $handler =<<<JS
    $(document).on('$event', '[data-remodal-id="$id"]', $handler);
JS;
        self::$events[$id][$event][] = $handler;
    }

    public static function Link($id, $label, $class = "")
    {
        return '<a href="javascript:void(0)" class="' . $class . '" data-remodal-target="' . $id . '">' . $label . '</a>';
    }

    public static function Button($id, $label, $class = "", $addition = "")
    {
        return '<button class="' . $class . '" data-remodal-target="' . $id . '" ' . $addition . '>' . $label . '</button>';
    }
}
