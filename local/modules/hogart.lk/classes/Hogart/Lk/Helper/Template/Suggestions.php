<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 05/09/16
 * Time: 13:48
 */

namespace Hogart\Lk\Helper\Template;


use Bitrix\Main\EventManager;
use Bitrix\Main\Page\Asset;

class Suggestions
{
    const SERVICE_URL = "https://suggestions.dadata.ru/suggestions/api/4_1/rs";

    protected static $SERVICE_URL;
    protected static $API_KEY;

    public static function init()
    {
        self::$SERVICE_URL = \COption::GetOptionString("hogart.lk", "DADATA_SERVICE_URL", self::SERVICE_URL);
        self::$API_KEY = \COption::GetOptionString("hogart.lk", "DADATA_API_KEY");

        Asset::getInstance()->addJs('//cdn.jsdelivr.net/jquery.suggestions/16.6/js/jquery.suggestions.min.js', true);
        Asset::getInstance()->addCss('//cdn.jsdelivr.net/jquery.suggestions/16.6/css/suggestions.css', true);
        Asset::getInstance()->addJs('/local/modules/hogart.lk/assets/hogart.lk/js/suggestions_ext.js');

        EventManager::getInstance()->addEventHandler("main", "OnEndBufferContent", ["Hogart\\Lk\\Helper\\Template\\Suggestions", "OnEndBufferContent"]);
    }

    public static function OnEndBufferContent(&$content)
    {
        $url = self::$SERVICE_URL;
        $key = self::$API_KEY;
        $dadata =<<<HTML
<script language="JavaScript">
window.DaData.token = "$key";
window.DaData.serviceUrl = "$url";
</script>
HTML;
        $content = preg_replace("#(</body>)#", "$dadata\n\\1", $content);
    }
}