<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 28/08/16
 * Time: 00:51
 */

namespace Hogart\Lk\Helper\Template;


class Ajax
{
    /** @var array|\CBitrixComponent[]  */
    private static $components = [];
    /** @var array|string[][] */
    private static $confirmations = [];

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
     * @param bool $confirm_dialog Нужен ли диалог с предупреждением
     * @param array $dialog_options Свойства диалога
     * @return string
     */
    public static function OnClickEvent($container, $ajax_id, $params = [], $confirm_dialog = false, $dialog_options = [])
    {
        global $APPLICATION;
        $url = $APPLICATION->GetCurUri(http_build_query(array_merge([BX_AJAX_PARAM_ID => $ajax_id], $params)));
        $html = "";
        $function = self::__load($url, $container);
        if (!$confirm_dialog) {
            $html .=<<<HTML
onclick="$function"
HTML;
        } else {
            $confirmation = implode("-", [$ajax_id, md5(serialize($params))]);

            ob_start();

            Dialog::Start($confirmation, $dialog_options['remodal_options']);
            $title = $dialog_options['title'];
            $confirm_message = $dialog_options['confirmation'];
            echo <<<HTML
<h3>$title</h3>   
<p>
    $confirm_message
</p> 
HTML;
            $id = Dialog::$id;
            $handler =<<<JS
(function() {
  var inst = $('[data-remodal-id="$id"]').remodal();
  $function
  inst.close();
})
JS;
            Dialog::Event('confirmation', $handler);
            Dialog::End();

            self::$confirmations[$ajax_id][$confirmation] = ob_get_clean();
            $html .= ' data-remodal-target="' . $confirmation . '"';
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