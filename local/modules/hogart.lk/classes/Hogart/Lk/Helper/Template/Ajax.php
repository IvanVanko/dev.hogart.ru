<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 28/08/16
 * Time: 00:51
 */

namespace Hogart\Lk\Helper\Template;


use Bitrix\Main\Page\Asset;

class Ajax
{
    const DIALOG_CONFIRMATION = 1;
    const DIALOG_EDIT = 2;

    /** @var array|\CBitrixComponent[]  */
    private static $components = [];
    /** @var array|string[][] */
    private static $confirmations = [];
    /** @var array|string[][] */
    private static $edit = [];

    private static function Init()
    {
        \CAjax::Init();
    }
    /**
     * @param \CBitrixComponent $component
     * @param array $params
     *e
     * @return bool|string
     */
    public static function Start(\CBitrixComponent $component, $params = [])
    {
        self::Init();
        $ajax_id = \CAjax::GetComponentId($component->getName(), $component->getTemplateName(), md5(serialize($params)));
        echo "<!-- " . $ajax_id . " -->";
        self::$components[$ajax_id] = $component;
        $component->getTemplate()->SetViewTarget($ajax_id);
        return $ajax_id;
    }
    /**
     * @param \CBitrixComponent $component
     * @param $ajax_id
     */
    public static function End(\CBitrixComponent $component, $ajax_id)
    {
        global $APPLICATION;
        $component->getTemplate()->EndViewTarget();
        if (self::isAjax($ajax_id)) {
            $APPLICATION->RestartBuffer();
        }
        $APPLICATION->ShowViewContent($ajax_id);
        while (($confirmation = array_shift(self::$confirmations[$ajax_id]))) {
            echo $confirmation;
        }
        while (($edit = array_shift(self::$edit[$ajax_id]))) {
            echo $edit;
        }

        if (self::isAjax($ajax_id)) {
            die();
        }
    }

    /**
     * @param $ajax_id
     * @return bool
     */
    private static function isAjax($ajax_id)
    {
        return $_SERVER['HTTP_BX_AJAX'] && \CAjax::GetSession() == $ajax_id;
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
        global $APPLICATION;
        $url = $APPLICATION->GetCurUri(http_build_query(array_merge([BX_AJAX_PARAM_ID => $ajax_id], $params)));
        $html = "";
        $function = self::__load($url, $container);
        if (!empty($dialog)) {
            Asset::getInstance()->addJs('/local/modules/hogart.lk/assets/hogart.lk/js/hogart.js');
            Asset::getInstance()->addJs('/local/modules/hogart.lk/assets/hogart.lk/js/remodal.ext.js');
            switch ($dialog) {
                case self::DIALOG_CONFIRMATION:
                    $confirmation_id = implode("-", [$ajax_id, md5(serialize(array_keys($params)))]);
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
                        onclick="openConfirmationDialog(\'' . $confirmation_id . '\', this)" 
                    ';
                    break;
                case self::DIALOG_EDIT:
                    $edit_id = implode("-", [$ajax_id, md5(serialize(array_keys($params)))]);
                    if (empty(self::$confirmations[$ajax_id][$edit_id])) {
                        ob_start();
                        Dialog::Start($edit_id, [
                            'dialog-options' => 'hashTracking: false, closeOnConfirm: false'
                        ]);
                        echo '<h3>' . htmlspecialchars($dialog_options['title']) . '</h3>';
                        echo '<form action="' . $APPLICATION->GetCurPage() . '" name="' . $dialog_options['edit_action'] . '" method="post">';
                        if (!empty($dialog_options['edit_form_file']) && file_exists($dialog_options['edit_form_file'])) {
                            include ($dialog_options['edit_form_file']);
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
                        onclick="openEditDialog(\'' . $edit_id . '\', this)"
                    ';
                    break;
            }
        } else {
            $html .=<<<HTML
onclick="$function"
HTML;
        }
        return $html;
    }

    /**
     * @param $url
     * @param $container
     * @return string
     */
    private static function __load($url, $container)
    {
        return "BX.ajax.insertToNode('{$url}', '{$container}');return false;";
    }

    /**
     * @see Ajax::Start
     * @param string $text Тест ссылки
     * @param string $container ID обновляемого контейнера
     * @param string $ajax_id Сгенерированный ранее AjaxID в методе Ajax::Start
     * @param array $params Параметры для GET запроса
     * @return string
     */
    public static function Link($text, $container, $ajax_id, $params = [])
    {
        $onclick = self::OnClickEvent($container, $ajax_id, $params);
        $html =<<<HTML
<a $onclick href="javascript:void(0);">$text</a>
HTML;
        return $html;
    }
}