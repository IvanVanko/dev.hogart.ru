<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 15/09/16
 * Time: 14:30
 */

namespace Hogart\Lk\Helper\Template;


use Bitrix\Main\Event;

class ViewNode
{
    const EVENT_ON_AJAX_VIEW_NODE = "OnAjaxViewNode";

    /** @var  string */
    protected $id;
    /** @var \CBitrixComponent  */
    protected $component;
    /** @var  ViewNode */
    protected $parent;
    /** @var  string */
    protected $parent_partial;
    /** @var ViewNode[]|array  */
    protected $child = [];
    /** @var bool  */
    protected $showed = false;
    /** @var bool  */
    protected $finished = false;

    /**
     * ViewNode constructor.
     * @param string $id
     * @param \CBitrixComponent $component
     * @param ViewNode $parent
     */
    public function __construct($id, \CBitrixComponent $component, ViewNode $parent = null)
    {
        $this->id = $id;
        $this->component = $component;
        if (null !== $parent) {
            $parent->addChild($this);
        }
        $this->parent = $parent;
        $this->component->getTemplate()->SetViewTarget($this->id);
    }

    protected function startParentPartial()
    {
        $id = uniqid($this->id);
        $this->parent_partial = $id;
        $this->component->getTemplate()->SetViewTarget($id);
    }

    protected function endView($is_ajax = false)
    {
        if (!$this->finished) {
            $this->finished = true;
            $this->component->getTemplate()->EndViewTarget();
            if (null !== $this->parent && !$is_ajax) {
                $this->startParentPartial();
            }
        }

        return $this;
    }

    public function view()
    {
        global $APPLICATION;

        $this->endView(Ajax::isAjax($this->id));
        if (Ajax::isAjax($this->id)) {
            $APPLICATION->RestartBuffer();
        }

        if (!$this->showed && (null === $this->parent && $this->finished) || (null !== $this->parent && ($this->parent->isFinished() || Ajax::isAjax($this->id)))) {
            $APPLICATION->ShowViewContent($this->id);
            foreach ($this->child as $childView) {
                $childView->view();
            }
            if ($this->parent_partial && !Ajax::isAjax($this->id)) {
                $APPLICATION->ShowViewContent($this->parent_partial);
            }
            $this->showed = true;
            if (Ajax::isAjax($this->id)) {
                $event = new Event("hogart.lk", self::EVENT_ON_AJAX_VIEW_NODE);
                $event->send($this);
            }
        }

    }

    public function includeFile($path, $parameters = [])
    {
        $path = realpath($path);
        if (file_exists($path)) {
            extract($parameters);
            include $path;
        }
    }

    public function addChild(ViewNode $child)
    {
        $this->child[$child->getId()] = $child;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \CBitrixComponent
     */
    public function getComponent()
    {
        return $this->component;
    }

    /**
     * @return ViewNode
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @return array|ViewNode[]
     */
    public function getChild()
    {
        return $this->child;
    }

    /**
     * @return boolean
     */
    public function isFinished()
    {
        return $this->finished;
    }
}
