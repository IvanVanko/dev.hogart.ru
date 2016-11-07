<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 23/09/2016
 * Time: 18:42
 */

namespace Hogart\Lk\Helper\Template;


use Hogart\Lk\Exception;

class FlashError extends Exception
{
    public function __construct($message, $delay = null, $redirect = null)
    {
        parent::__construct($message);
        $message = new Message($message, Message::SEVERITY_DANGER, $this);
        $message->setIcon('fa fa-exclamation-triangle');
        if (null !== $delay) {
            $message->setDelay($delay);
        }
        if (null !== $redirect) {
            $message->setUrl($redirect)->setRedirect(true);
        }
        MessageFactory::getInstance()->addFlashMessage($message);
    }
}