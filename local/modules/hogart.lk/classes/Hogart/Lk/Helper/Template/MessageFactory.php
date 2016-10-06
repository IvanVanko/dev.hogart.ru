<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 23/09/2016
 * Time: 16:30
 */

namespace Hogart\Lk\Helper\Template;


use Hogart\Lk\Creational\Singleton;

class MessageFactory
{
    use Singleton;

    const FLASH_MESSAGES_KEY = 'HOGART_FLASH_MESSAGES';

    /** @var array  */
    protected $messages = [];

    /**
     * @param IMessage $message
     * @return IMessage
     */
    public function addMessage(IMessage $message)
    {
        $this->messages[$message->getUnique()] = $message;
        return $message;
    }

    /**
     * @param IMessage $message
     * @return IMessage
     */
    public function addFlashMessage(IMessage $message)
    {
        $message = $this->addMessage($message);
        $flashMessage = unserialize($_SESSION[self::FLASH_MESSAGES_KEY]) ? : [];
        $flashMessage[$message->getUnique()] = $message;
        $_SESSION[self::FLASH_MESSAGES_KEY] = serialize($flashMessage);

        return $message;
    }

    /**
     * @return \ArrayIterator|Message[]
     */
    public function getFlashMessages()
    {
        $flashMessage = unserialize($_SESSION[self::FLASH_MESSAGES_KEY]) ? : [];
        unset($_SESSION[self::FLASH_MESSAGES_KEY]);
        return new \ArrayIterator($flashMessage);
    }

    /**
     * @return \ArrayIterator|Message[]
     */
    public function getMessages()
    {
        return new \ArrayIterator($this->messages);
    }

    /**
     * @return \ArrayIterator|Message[]
     */
    public function getAllMessages()
    {
        return new \ArrayIterator(array_merge($this->getMessages()->getArrayCopy(), $this->getFlashMessages()->getArrayCopy()));
    }
}
