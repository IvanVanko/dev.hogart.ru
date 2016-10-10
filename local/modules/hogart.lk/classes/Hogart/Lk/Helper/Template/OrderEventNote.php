<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 03/10/2016
 * Time: 02:35
 */

namespace Hogart\Lk\Helper\Template;


use Bitrix\Main\Type\Date;

class OrderEventNote
{
    /** @var  string */
    protected $guid;
    /** @var  string */
    protected $title;
    /** @var Date  */
    protected $date;
    /** @var  string */
    protected $badge_icon;
    /** @var  string */
    protected $badge_class;
    /** @var  string */
    protected $link;
    /** @var  string */
    protected $template_file;
    /** @var  array */
    protected $template_data;
    /** @var  string */
    protected $body;

    /**
     * OrderEventNote constructor.
     * @param string|null $title
     * @param Date|null $date
     */
    public function __construct($title = null, Date $date = null)
    {
        $this->title = $title;
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getGuid()
    {
        return $this->guid;
    }

    /**
     * @param string $guid
     * @return $this
     */
    public function setGuid($guid)
    {
        $this->guid = $guid;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param null $template_path
     * @return mixed
     */
    public function getBody($template_path = null)
    {
        if (!empty($this->template_file) && file_exists(($template = realpath(realpath($template_path) . "/" . $this->template_file)))) {
            ob_start();
            extract($this->template_data);
            @include $template;
            $html = ob_get_contents();
            ob_end_clean();
            return $html;
        }
        return "<p>" . $this->body . "</p>";
    }

    /**
     * @param string $body
     * @return $this
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @return Date|null
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     * @return $this
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBadgeIcon()
    {
        return $this->badge_icon;
    }

    /**
     * @param mixed $badge_icon
     * @return $this
     */
    public function setBadgeIcon($badge_icon)
    {
        $this->badge_icon = $badge_icon;
        return $this;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param string $link
     * @return $this
     */
    public function setLink($link)
    {
        $this->link = $link;
        return $this;
    }

    /**
     * @return string
     */
    public function getBadgeClass()
    {
        return $this->badge_class;
    }

    /**
     * @param string $badge_class
     * @return $this
     */
    public function setBadgeClass($badge_class)
    {
        $this->badge_class = $badge_class;
        return $this;
    }

    /**
     * @param string $template_file
     * @return $this
     */
    public function setTemplateFile($template_file)
    {
        $this->template_file = $template_file;
        return $this;
    }

    /**
     * @param array $template_data
     * @return $this
     */
    public function setTemplateData($template_data)
    {
        $this->template_data = $template_data;
        return $this;
    }
}
