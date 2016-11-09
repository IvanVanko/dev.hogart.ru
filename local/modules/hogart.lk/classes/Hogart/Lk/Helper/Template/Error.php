<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 23/09/2016
 * Time: 18:29
 */

namespace Hogart\Lk\Helper\Template;


use Hogart\Lk\Exception;

class Error extends Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
        $message = new Message($message, Message::SEVERITY_DANGER, $this);
        $message->setIcon('fa fa-exclamation-triangle');
        MessageFactory::getInstance()->addMessage($message);
    }
}