<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 25/09/2016
 * Time: 14:08
 */

namespace Hogart\Lk\Helper\Template;


use Bitrix\Main\EventManager;
use Hogart\Lk\Entity\AccountTable;
use Hogart\Lk\Entity\FlashMessagesTable;

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
        if (
            (!defined('ADMIN_SECTION') || !ADMIN_SECTION)
            && !preg_match('%application/json%', $_SERVER['HTTP_ACCEPT'])
            && !preg_match('%text/event-stream%', $_SERVER['HTTP_ACCEPT'])
        ) {

            global $USER;
            $account = AccountTable::getAccountByUserID($USER->GetID());
            if (!empty($account['id'])) {
                FlashMessagesTable::getMessages($account['id']);
            }

            $messages = MessageFactory::getInstance()->getAllMessages();
            if ($messages->count()) {
                $content .= '<script language="JavaScript" type="text/javascript">';
                foreach ($messages as $message) {
                    $severity = $message->getSeverity();
                    $icon = $message->getIcon();
                    $url = $message->getUrl();
                    $delay = $message->getDelay() * 1000;
                    $allow_dismiss = !$message->getDelay() ? : false;
                    $content .=<<<JS
$(function () {
  $.notify({
    url: '$url' || null,
	icon: '$icon' || null,
	message: '$message'
  }, { type: '$severity', delay: '$delay', allow_dismiss: '$allow_dismiss' });
});            
JS;
                }
                $content .= '</script>';
            }
        }
    }
}