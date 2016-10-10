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
    const SEVERITY_SUCCESS = 'success';

    /** @var string  */
    protected $message;
    /** @var  string */
    protected $severity;
    /** @var  string */
    protected $url;
    /** @var  string */
    protected $icon;
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
}