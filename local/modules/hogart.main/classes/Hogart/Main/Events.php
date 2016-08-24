<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 24/08/16
 * Time: 15:02
 */

namespace Hogart\Main;


class Events
{
    public static function GTMEnable(&$content)
    {
        if (
            \COption::GetOptionString("hogart.main", "GTM_ON") == "Y" 
            && ($gtm_code = \COption::GetOptionString("hogart.main", "GTM_TRACKING_CODE")) 
            && !empty($gtm_code)
            && !defined("ADMIN_SECTION")
        ) {
            $gtm =<<<HTML
<!— Google Tag Manager —>
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=$gtm_code"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<script😠function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','$gtm_code');</script>
<!— End Google Tag Manager —>
HTML;
            $content = preg_replace("#(<body[^>]*>)#", "\\1\n$gtm", $content);
        }
    }
}