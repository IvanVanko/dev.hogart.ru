<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 25/09/2016
 * Time: 14:08
 */

namespace Hogart\Lk\Helper\Template;


use Bitrix\Main\EventManager;

class Notification
{
    public static function init()
    {
        $OnEndBufferContentKey = EventManager::getInstance()->addEventHandler("main", "OnEndBufferContent", ["\\Hogart\\Lk\\Helper\\Template\\Notification", "OnEndBufferContent"]);
        EventManager::getInstance()->addEventHandler("main", "OnBeforeLocalRedirect", function () use ($OnEndBufferContentKey) {
            $factory = MessageFactory::getInstance();
            foreach ($factory->getAllMessages() as $message) {
                $factory->addFlashMessage($message);
            }
            EventManager::getInstance()->removeEventHandler("main", "OnEndBufferContent", $OnEndBufferContentKey);
        });
    }

    public function OnEndBufferContent (&$content)
    {
        if ((!defined('ADMIN_SECTION') || !ADMIN_SECTION) && !preg_match('%application/json%', $_SERVER['HTTP_ACCEPT'])) {
            $messages = MessageFactory::getInstance()->getAllMessages();
            if ($messages->count()) {
                $content .= '<script language="JavaScript" type="text/javascript">';
                foreach ($messages as $message) {
                    $severity = $message->getSeverity();
                    $icon = $message->getIcon();
                    $url = $message->getUrl();
                    $content .=<<<JS
$(function () {
  $.notify({
    url: '$url' || null,
	icon: '$icon' || null,
	message: '$message'
  }, { type: '$severity' });
});            
JS;
                }
                $content .= '</script>';
            }
        }
    }
}