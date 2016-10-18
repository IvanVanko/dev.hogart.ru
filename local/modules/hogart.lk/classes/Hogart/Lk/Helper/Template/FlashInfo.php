<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 25/09/2016
 * Time: 14:27
 */

namespace Hogart\Lk\Helper\Template;


class FlashInfo
{
    /**
     * FlashInfo constructor.
     * @param $message
     * @param null|string $url
     */
    public function __construct($message, $url = null, $delay = null)
    {
        $message = new Message($message, Message::SEVERITY_INFO);
        $message->setUrl($url);
        if (null !== $url) {
            $message->setIcon('fa fa-external-link');
        }
        if (null !== $delay) {
            $message->setDelay($delay);
        }
        MessageFactory::getInstance()->addFlashMessage($message);
    }
}