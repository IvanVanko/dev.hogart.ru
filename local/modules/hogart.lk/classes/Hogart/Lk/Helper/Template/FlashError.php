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
    public function __construct($message)
    {
        parent::__construct($message);
        $message = new Message($message, Message::SEVERITY_DANGER, $this);
        $message->setIcon('fa fa-exclamation-triangle');
        MessageFactory::getInstance()->addFlashMessage($message);
    }
}