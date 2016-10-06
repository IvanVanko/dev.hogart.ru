<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 28/08/16
 * Time: 00:51
 */

namespace Hogart\Lk\Helper\Template;


use Bitrix\Main\Event;
use Bitrix\Main\Page\Asset;

class Ajax
{
    const DIALOG_CONFIRMATION = 1;
    const DIALOG_EDIT = 2;
    const DIALOG_AJAX_LINK = 3;

    /** @var array|string[][] */
    private static $confirmations = [];
    /** @var array|string[][] */
    private static $edit = [];
    /** @var ViewNode[]|array */
    public static $nodes = [];

    private static function Init()
    {
        \CAjax::Init();
    }

    public static function GetAjaxId(\CBitrixComponent $component, $params = [])
    {
        return \CAjax::GetComponentID($component->getName(), $component->getTemplateName(), md5(serialize($params)));
    }

    /**
     * @param \CBitrixComponent $component
     * @param array $params
     * @param ViewNode $parentViewNode
     * @param null|string $ajax_id
     * @return bool|ViewNode
     */
    public static function Start(\CBitrixComponent $component, $params = [], ViewNode $parentViewNode = null, $ajax_id = null)
    {
        self::Init();
        if (null === $ajax_id) {
            $ajax_id = self::GetAjaxId($component, $params);
        }
        if (!isset(self::$nodes[$ajax_id])) {
            echo "<!-- " . $ajax_id . " -->";
            self::$nodes[$ajax_id] = new ViewNode($ajax_id, $component, $parentViewNode);
        }
        return self::$nodes[$ajax_id];
    }

    /**
     * @param $ajax_id
     */
    public static function End($ajax_id)
    {
        self::$nodes[$ajax_id]->view();

        if (!self::isAjax($ajax_id)) {
            while (($confirmation = array_shift(self::$confirmations[$ajax_id]))) {
                echo $confirmation;
            }
            while (($edit = array_shift(self::$edit[$ajax_id]))) {
                echo $edit;
            }
        }

        if (self::isAjax($ajax_id)) {
            die();
        }
    }

    /**
     * @param $ajax_id
     * @return bool
     */
    public static function isAjax($ajax_id = null)
    {
        return
            $_SERVER['HTTP_BX_AJAX']
            && (null !== $ajax_id ? \CAjax::GetSession() == $ajax_id : true)
        ;
    }

    /**
     * @see Ajax::Start
     * @param string $container ID обновляемого контейнера
     * @param string $ajax_id Сгенерированный ранее AjaxID в методе Ajax::Start
     * @param array $params Параметры для GET запроса
     * @param bool $dialog Нужен ли диалог с предупреждением
     * @param array $dialog_options Свойства диалога
     * @return string
     */
    public static function OnClickEvent($container, $ajax_id, $params = [], $dialog = false, $dialog_options = [])
    {
        return self::OnEvent('click', $container, $ajax_id, $params, $dialog, $dialog_options);
    }

    public static function OnEvent($event = 'click', $container, $ajax_id, $params = [], $dialog = false, $dialog_options = [])
    {
        global $APPLICATION;
        $html = "";
        $killParams = array_merge(array_keys($params), [BX_AJAX_PARAM_ID]);
        $__html = "";
        foreach ($params as $key => $param) {
            if (preg_match("%^javascript:(?P<function>.*?)$%", $param, $m)) {
                $__html .= '_[\'' . $key . '\'] = ' . htmlspecialchars($m['function']) . '';
                unset($params[$key]);
            }
        }
        if (!empty($__html)) {
            $html .= 'data-onchangeurl=" ' . $__html .  ' "';
        }
        $url = $APPLICATION->GetCurPageParam(http_build_query(array_merge([BX_AJAX_PARAM_ID => $ajax_id], $params)), $killParams, false);
        $function = self::__load($ajax_id, $url, $container);
        if (!empty($dialog)) {
            switch ($dialog) {
                case self::DIALOG_CONFIRMATION:
                    $confirmation_id = implode("-", [$ajax_id, md5(serialize(array_keys($params))), md5(serialize($dialog_options['dialog_keys']))]);
                    $title = htmlspecialchars($dialog_options['title']);
                    $confirm_message = htmlspecialchars($dialog_options['confirmation']);
                    if (empty(self::$confirmations[$ajax_id][$confirmation_id])) {
                        ob_start();
                        Dialog::Start($confirmation_id, [
                            'dialog-options' => 'hashTracking: false, closeOnConfirm: false'
                        ]);
                        echo '<div data-confirmation-wrapper></div>';
                        Dialog::End();
                        self::$confirmations[$ajax_id][$confirmation_id] = ob_get_clean();
                    }
                    $html .= '
                        data-confirmation-message="' . $confirm_message . '" 
                        data-confirmation-title="' . $title . '"
                        data-confirmation-function="' . $function . '"
                        on' . $event . '="openConfirmationDialog(\'' . $confirmation_id . '\', this)" 
                    ';
                    break;
                case self::DIALOG_AJAX_LINK:
                    $dialog_id = implode("-", [$ajax_id, md5(serialize(array_keys($params))), md5(serialize($dialog_options['dialog_keys']))]);

                    if (empty(self::$confirmations[$ajax_id][$dialog_id])) {
                        ob_start();
                        Dialog::Start($dialog_id, [
                            'dialog-options' => 'hashTracking: false, closeOnConfirm: false'
                        ]);
                        echo '<h3>' . htmlspecialchars($dialog_options['title']) . '</h3>';
                        if (!empty($dialog_options['template_file'])) {
                            self::$nodes[$ajax_id]->includeFile($dialog_options['template_file'], $dialog_options['template_vars']);
                        }
                        Dialog::End();
                        self::$confirmations[$ajax_id][$dialog_id] = ob_get_clean();
                    }

                    $events = array_reduce(array_keys($dialog_options), function ($result, $item) use($dialog_options) {
                        if (preg_match("%^dialog_event_(?P<event>.*)%", $item, $m)) {
                            $result[$m['event']] = $dialog_options["dialog_event_" . $m['event']];
                        }
                        return $result;
                    }, []);
                    if (!empty($events)) {
                        $html .= 'data-dialog-events="' . \CUtil::PhpToJSObject($events) . '"' ;
                    }
                    $html .= '
                        data-link="' . $function . '"
                        on' . $event . '="openAjaxLinkDialog(\'' . $dialog_id . '\', this)"
                    ';
                    break;
                case self::DIALOG_EDIT:
                    $edit_id = implode("-", [$ajax_id, md5(serialize(array_keys($params))), md5(serialize($dialog_options['dialog_keys']))]);
                    if (empty(self::$confirmations[$ajax_id][$edit_id])) {
                        ob_start();
                        Dialog::Start($edit_id, [
                            'dialog-options' => 'hashTracking: false, closeOnConfirm: false'
                        ]);
                        echo '<h3>' . htmlspecialchars($dialog_options['title']) . '</h3>';
                        echo '<form action="' . $APPLICATION->GetCurPage() . '" name="' . $dialog_options['edit_action'] . '" method="post">';
                        if (!empty($dialog_options['template_file'])) {
                            self::$nodes[$ajax_id]->includeFile($dialog_options['template_file'], $dialog_options['template_vars']);
                        }
                        echo '<input data-bind="__action" type="hidden" name="action">';
                        echo '<input type="hidden" name="id" value="">';
                        echo '</form>';
                        Dialog::End();
                        self::$confirmations[$ajax_id][$edit_id] = ob_get_clean();
                    }

                    $events = array_reduce(array_keys($dialog_options), function ($result, $item) use($dialog_options) {
                        if (preg_match("%^dialog_event_(?P<event>.*)%", $item, $m)) {
                            $result[$m['event']] = $dialog_options["dialog_event_" . $m['event']];
                        }
                        return $result;
                    }, []);

                    if (!empty($events)) {
                        $html .= 'data-dialog-events="' . \CUtil::PhpToJSObject($events) . '"' ;
                    }

                    $html .= '
                        data-edit="' . \CUtil::PhpToJSObject(array_merge($dialog_options['edit_object'], ['__action' => $dialog_options['edit_action']])) . '"
                        on' . $event . '="openEditDialog(\'' . $edit_id . '\', this)"
                    ';
                    break;
            }
        } else {
            $html .=<<<HTML
on$event="$function"
HTML;
        }
        return $html;
    }

    public static function OnClickUrl($ajax_id, $url, $container)
    {
        $query = "?" . BX_AJAX_PARAM_ID . "=" . $ajax_id;
        $url = preg_replace("#\\?(.*)#", $query . "&\\1", $url);
        return 'onclick="' . self::__load($ajax_id, $url, $container) . '"';
    }

    /**
     * @param $ajax_id
     * @param $url
     * @param $container
     * @return string
     */
    protected static function __load($ajax_id, $url, $container)
    {
        return "Hogart_Lk.insertToNode(Hogart_Lk.getAjaxUrl(this, '{$ajax_id}', '{$url}'), '{$container}');return false;";
    }

    /**
     * @see Ajax::Start
     * @param string $text Тест ссылки
     * @param string $container ID обновляемого контейнера
     * @param string $ajax_id Сгенерированный ранее AjaxID в методе Ajax::Start
     * @param array $params Параметры для GET запроса
     * @param string $ext
     * @param bool $dialog
     * @param array $dialog_options
     * @return string
     */
    public static function Link($text, $container, $ajax_id, $params = [], $ext = '', $dialog = false, $dialog_options = [])
    {
        $onclick = self::OnClickEvent($container, $ajax_id, $params, $dialog, $dialog_options);
        $html =<<<HTML
<a $ext $onclick href="javascript:void(0);">$text</a>
HTML;
        return $html;
    }
}