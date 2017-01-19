<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 27/07/16
 * Time: 20:17
 */

/** @var Composer\Autoload\ClassLoader $classloader */
$classloader = require_once dirname(__FILE__) . "/vendor/autoload.php";
$classloader->add("Hogart\\Lk", dirname(__FILE__) . '/classes');

CModule::IncludeModule("main");
CModule::IncludeModule("catalog");

$currencies = \Bitrix\Currency\CurrencyTable::getList([
    'filter' => [
        '=CURRENCY' => [
            'RUB',
            'EUR'
        ]
    ],
    'select' => [
        '*',
        'LANG_' => 'CURRENT_LANG_FORMAT'
    ]
])->fetchAll();

CStorage::setVar($currencies, 'HOGART.CURRENCIES');

$tmp = $_SERVER['DOCUMENT_ROOT'] . "/local/modules/hogart.lk/tmp";
$pdf = $_SERVER['DOCUMENT_ROOT'] . "/local/modules/hogart.lk/pdf";
$reports = $_SERVER['DOCUMENT_ROOT'] . "/local/modules/hogart.lk/reports";
if (!file_exists($tmp)) {
    mkdir($tmp, 0777, true);
}
if (!file_exists($pdf)) {
    mkdir($pdf, 0777, true);
}
if (!file_exists($reports)) {
    mkdir($reports, 0777, true);
}
define("HOGART_PDF_DIR", $pdf);
define("HOGART_TMP_DIR", $tmp);
define("HOGART_REPORTS_DIR", $reports);
define("HOGART_DATE_FORMAT", "d.m.Y");
define("HOGART_DATE_TIME_FORMAT", "d.m.Y H:i");

\Hogart\Lk\Helper\Template\Notification::init();

\Bitrix\Main\Page\Asset::getInstance()->addJs('//cdnjs.cloudflare.com/ajax/libs/event-source-polyfill/0.0.7/eventsource.min.js', true);

\Bitrix\Main\Page\Asset::getInstance()->addJs('//cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.5/validator.min.js', true);

\Bitrix\Main\Page\Asset::getInstance()->addCss('//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/css/jasny-bootstrap.min.css', true);
\Bitrix\Main\Page\Asset::getInstance()->addJs('//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/js/jasny-bootstrap.min.js', true);

\Bitrix\Main\Page\Asset::getInstance()->addCss('//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.0/css/bootstrap-select.min.css', true);
\Bitrix\Main\Page\Asset::getInstance()->addJs('//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.0/js/bootstrap-select.min.js', true);
\Bitrix\Main\Page\Asset::getInstance()->addJs('//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.0/js/i18n/defaults-ru_RU.min.js', true);

\Bitrix\Main\Page\Asset::getInstance()->addJs('/local/modules/hogart.lk/assets/hogart.lk/js/hogart.js', true);
\Bitrix\Main\Page\Asset::getInstance()->addJs('/local/modules/hogart.lk/assets/snap.svg/snap.svg-min.js', true);
\Bitrix\Main\Page\Asset::getInstance()->addJs('/local/modules/hogart.lk/assets/hogart.lk/js/svg.js', true);
\Bitrix\Main\Page\Asset::getInstance()->addJs('/local/modules/hogart.lk/assets/hogart.lk/js/remodal.ext.js', true);
\Bitrix\Main\Page\Asset::getInstance()->addJs('/local/modules/hogart.lk/assets/hogart.lk/js/jquery.input-apply.js', true);
\Bitrix\Main\Page\Asset::getInstance()->addCss('/local/modules/hogart.lk/assets/hogart.lk/less/styles.css', true);
\Bitrix\Main\Page\Asset::getInstance()->addCss('/local/modules/hogart.lk/assets/hogart.lk/less/jquery.input-apply.css', true);

\Bitrix\Main\Page\Asset::getInstance()->addJs('//cdnjs.cloudflare.com/ajax/libs/moment.js/2.14.1/moment.min.js', true);
\Bitrix\Main\Page\Asset::getInstance()->addJs('//cdnjs.cloudflare.com/ajax/libs/moment.js/2.14.1/locale/ru.js', true);

\Bitrix\Main\Page\Asset::getInstance()->addCss('/local/modules/hogart.lk/assets/datatables/datatables.min.css', true);
\Bitrix\Main\Page\Asset::getInstance()->addJs('/local/modules/hogart.lk/assets/datatables/datatables.min.js', true);

\Bitrix\Main\Page\Asset::getInstance()->addJs('//cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js', true);

\Bitrix\Main\Page\Asset::getInstance()->addCss('//cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css', true);
\Bitrix\Main\Page\Asset::getInstance()->addJs('//cdn.rawgit.com/leafo/sticky-kit/v1.1.2/jquery.sticky-kit.min.js', true);

\Bitrix\Main\Page\Asset::getInstance()->addJs('//cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.3.5/js/fileinput.min.js', true);
\Bitrix\Main\Page\Asset::getInstance()->addJs('//cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.3.5/js/locales/ru.min.js', true);
\Bitrix\Main\Page\Asset::getInstance()->addCss('//cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.3.5/css/fileinput.min.css', true);
\Bitrix\Main\Page\Asset::getInstance()->addJs('//cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.3.5/themes/fa/theme.min.js', true);

\Bitrix\Main\Page\Asset::getInstance()->addJs('//bootstrap-notify.remabledesigns.com/js/bootstrap-notify.min.js', true);

\Bitrix\Main\Page\Asset::getInstance()->addJs('//cdnjs.cloudflare.com/ajax/libs/corejs-typeahead/0.11.1/typeahead.jquery.min.js', true);

\Bitrix\Main\Page\Asset::getInstance()->addJs('//cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.5/handlebars.min.js', true);

\Bitrix\Main\Page\Asset::getInstance()->addCss('/local/modules/hogart.lk/assets/hogart.lk/less/bootstrap-datetimepicker.css', true);
\Bitrix\Main\Page\Asset::getInstance()->addJs('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.42/js/bootstrap-datetimepicker.min.js', true);

\Bitrix\Main\Page\Asset::getInstance()->addJs('//cdnjs.cloudflare.com/ajax/libs/Sortable/1.4.2/Sortable.min.js', true);

\Bitrix\Main\Page\Asset::getInstance()->addJs('/local/modules/hogart.lk/assets/tree-multiselect/jquery.tree-multiselect.min.js', true);
\Bitrix\Main\Page\Asset::getInstance()->addCss('/local/modules/hogart.lk/assets/tree-multiselect/jquery.tree-multiselect.min.css', true);