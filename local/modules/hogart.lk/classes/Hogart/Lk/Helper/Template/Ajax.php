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
     * @return string
     */
    public static function OnClickEvent($container, $ajax_id, $params = [])
    {
        global $APPLICATION;
        $url = $APPLICATION->GetCurUri(http_build_query(array_merge([BX_AJAX_PARAM_ID => $ajax_id], $params)));
        $html =<<<HTML
onclick="BX.ajax.insertToNode('$url', '$container');return false;"
HTML;
        return $html;
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