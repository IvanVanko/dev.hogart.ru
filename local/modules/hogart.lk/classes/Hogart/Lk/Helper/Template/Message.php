<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 23/09/2016
 * Time: 16:38
 */

namespace Hogart\Lk\Helper\Template;


class Message implements IMessage
{
    const SEVERITY_INFO = 'info';
    const SEVERITY_DANGER = 'danger';
    const SEVERITY_WARNING = 'warning';
    const SEVERITY_SUCCESS = 'success';

    /** @var string  */
    protected $message;
    /** @var  string */
    protected $severity;
    /** @var  string */
    protected $url;
    /** @var  string */
    protected $icon;
    /** @var  integer */
    protected $delay = 5;
    /** @var bool  */
    protected $redirect = false;
    /** @var string  */
    protected $file;
    /** @var int  */
    protected $line;
    /** @var  array */
    protected $trace;

    /**
     * ErrorMessage constructor.
     * @param string $message
     * @param string $severity
     * @param \Exception $exception
     */
    public function __construct($message, $severity, \Exception $exception = null)
    {
        $this->message = $message;
        $this->severity = $severity;
        if (null !== $exception) {
            $this->line = $exception->getLine();
            $this->file = $exception->getFile();
            $this->trace = $exception->getTrace();
        }
    }

    /**
     * The __toString method allows a class to decide how it will react when it is converted to a string.
     *
     * @return string
     * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
     */
    function __toString()
    {
        return $this->message;
    }

    function getMessage()
    {
        return $this->message;
    }

    /**
     * @return boolean
     */
    public function isRedirect()
    {
        return !empty($this->url) && $this->redirect;
    }

    /**
     * @param boolean $redirect
     * @return $this
     */
    public function setRedirect($redirect)
    {
        $this->redirect = $redirect;
        return $this;
    }

    /**
     * @return int
     */
    public function getDelay()
    {
        return $this->delay;
    }

    /**
     * @param int $delay
     * @return $this
     */
    public function setDelay($delay)
    {
        $this->delay = $delay;
        return $this;
    }

    /**
     * @return string
     */
    function getSeverity()
    {
        return (string)$this->severity;
    }

    /**
     * @return string
     */
    function getUnique()
    {
        return md5(implode('|', [$this->message, $this->severity, $this->icon, $this->url, $this->line, $this->file]));
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     * @return $this
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
        return $this;
    }

    /**
     * @return string
     */
    public function toJSON()
    {
        return json_encode([
            'type' => 'notify',
            'data' => [
                [
                    'url' => $this->url,
                    'message' => $this->message,
                    'icon' => $this->icon
                ],
                [
                    'type' => $this->severity,
                    'delay' => $this->delay * 1000,
                    'allow_dismiss' => !$this->delay ? : false,
                    '__redirect' => $this->isRedirect()
                ]
            ]
        ]);
    }
}