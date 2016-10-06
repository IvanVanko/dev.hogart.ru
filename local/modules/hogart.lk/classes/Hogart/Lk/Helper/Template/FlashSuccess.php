<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 26/09/2016
 * Time: 20:14
 */

namespace Hogart\Lk\Helper\Template;


class FlashSuccess
{

    /**
     * FlashSuccess constructor.
     * @param string $message
     * @param null|string $url
     */
    public function __construct($message, $url = null)
    {
        $message = new Message($message, Message::SEVERITY_SUCCESS);
        $message->setIcon('fa fa-check');
        $message->setUrl($url);
        if (null !== $url) {
            $message->setIcon('fa fa-external-link');
        }
        MessageFactory::getInstance()->addFlashMessage($message);
    }
}