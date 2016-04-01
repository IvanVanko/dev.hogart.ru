<?php

/*
  if(!is_object($USER)) $USER = new CUser;
  if ($USER->isAdmin() && $_REQUEST["IBLOCK_ID"]==22 && ($_REQUEST["ID"]==0 || $_REQUEST["ID"]>0)) {
  global $APPLICATION;
  $APPLICATION->AddHeadScript(DEFAULT_TEMPLATE_PATH_CUSTOM."js/admin.js");
  }
 */

function cropText($text, $descLength = 25) {
    $previewText = (strlen($text) > $descLength + 3) ? substr($text, 0, $descLength) . '...' : $text;
    return $previewText;
}

function setPageClass($class) {
    global $APPLICATION;
    $APPLICATION->SetPageProperty("page_class", $class);
}

function getPageClass() {
    global $APPLICATION;
    $APPLICATION->GetPageProperty("page_class");
}

function isHome() {
    global $APPLICATION;
    return $APPLICATION->GetCurPage() == SITE_DIR ? true : false;
}

function vd($data) {
    global $USER;
    if ($USER->IsAdmin()) {
        echo "<pre>";
        var_dump($data);
        echo "</pre>";
    }
}

function pr($data, $not_admin = false) {
    global $USER;
    if ($USER->IsAdmin() || $not_admin) {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }
}

function abs_path($path) {
    if (is_string($path) && strlen($path) > 0) {
        return $_SERVER['DOCUMENT_ROOT'] . $path;
    }
    return false;
}

function rel_path($path) {
    if (is_string($path) && strlen($path) > 0) {
        return preg_replace('/' . str_replace("/", "\\/", $_SERVER['DOCUMENT_ROOT']) . '/', '', $path);
    }
    return false;
}

function http_path($rel_path) {
    if (file_exists($_SERVER['DOCUMENT_ROOT'] . $rel_path)) {
        return $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['SERVER_NAME'] . $rel_path;
    }
    return false;
}

function caniget($var, $replace = false/* ,$check_function */) {
    /* if (!is_callable($check_function)) {

      } */
    if (!empty($var)) {
        return $var;
    } else {
        return $replace;
    }
}

function is_assoc($arr)
{
    return array_keys($arr) !== range(0, count($arr) - 1);
}

function fileDump($ar, $append) {
    if ($append) {
        $append = FILE_APPEND;
    }
    if (is_object($ar) || is_callable($ar) || is_resource($ar) || is_bool($ar) || is_null($ar)) {

        ob_start();
        var_dump($ar);
        $dump = ob_get_clean();

        file_put_contents(abs_path("/dump.html"), strip_tags($dump), $append);
    } else {
        file_put_contents(abs_path("/dump.html"), print_r($ar, true), $append);
    }
}

function jsDump($ar) {
    global $USER;
    if ($USER->IsAdmin()) {
        echo "<script>var init = true; console.log(JSON.parse('" . addslashes(json_encode($ar)) . "'))</script>";
    }
}

function isAjax() {
    // для запроса библиотекой pjax возвращаем false, чтобы не ломалась аяксовая подгрузка страниц
    return !isset($_SERVER['HTTP_X_PJAX']) && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

function isPAjax() {
    return isAjax() && (isset($_SERVER['HTTP_X_PJAX']) && !empty($_SERVER['HTTP_X_PJAX'])) ? true : false;
}

function isMobile() {
    if (!session_id()) {
        session_start();
    }
    if (isset($_SESSION['show-mobile-version']) ||
            (defined('IS_PHONE') && IS_PHONE) ||
            (defined('IS_TABLET') && IS_TABLET)
    ) {
        return true;
    }

    if (function_exists('mobile_device_detect')) {
        return mobile_device_detect();
    }

    session_write_close();
    return false;
}

function declOfNum($number, $titles) {
    $cases = array(2, 0, 1, 1, 1, 2);
    return $titles[($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[min($number % 10, 5)]];
}

function toTranslit($text, $replace_space = "-", $replace_other = "-") {
    return CUtil::translit($text, "ru", array("replace_space" => $replace_space, "replace_other" => $replace_other));
}

function setContent($name, $content) {
    global $APPLICATION;
    $APPLICATION->AddViewContent($name, $content);
}

// отложенная функция, содержимое выводится после полной
// буферизации вывода
function showContent($name) {
    global $APPLICATION;
    $APPLICATION->ShowViewContent($name);
}

function setFlash($text) {
    if (strlen(trim($text)) > 0) {
        session_start();
        $_SESSION['flash_message'] = trim($text);
        return true;
    }
    return false;
}

function showFlash($class = null, $template = null) {

    $class = is_null($class) ? "" : $class;
    $template = is_null($template) ? "<div class='flash_message #class#'>#message</div>" : $template;

    session_start();
    if (is_string($_SESSION['flash_message']) && strlen(trim($_SESSION['flash_message'])) > 0) {
        $search = array(
            "#class#",
            "#message"
        );
        $replace = array(
            $class,
            trim($_SESSION['flash_message'])
        );
        echo str_replace($search, $replace, $template);
        unset($_SESSION['flash_message']);
    }
}

function getFlash() {
    session_start();
    return is_string($_SESSION['flash_message']) && strlen(trim($_SESSION['flash_message'])) > 0 ?
            trim($_SESSION['flash_message']) : false;
}

function decode_entities_full($string, $quotes = ENT_COMPAT, $charset = 'cp1251') {
    return html_entity_decode(preg_replace_callback('/&([a-zA-Z][a-zA-Z0-9]+);/', 'convert_entity', $string), $quotes, $charset);
}

function convert_entity($matches, $destroy = true) {
    static $table = array('quot' => '&#34;', 'amp' => '&#38;', 'lt' => '&#60;', 'gt' => '&#62;', 'OElig' => '&#338;', 'oelig' => '&#339;', 'Scaron' => '&#352;', 'scaron' => '&#353;', 'Yuml' => '&#376;', 'circ' => '&#710;', 'tilde' => '&#732;', 'ensp' => '&#8194;', 'emsp' => '&#8195;', 'thinsp' => '&#8201;', 'zwnj' => '&#8204;', 'zwj' => '&#8205;', 'lrm' => '&#8206;', 'rlm' => '&#8207;', 'ndash' => '&#8211;', 'mdash' => '&#8212;', 'lsquo' => '&#8216;', 'rsquo' => '&#8217;', 'sbquo' => '&#8218;', 'ldquo' => '&#8220;', 'rdquo' => '&#8221;', 'bdquo' => '&#8222;', 'dagger' => '&#8224;', 'Dagger' => '&#8225;', 'permil' => '&#8240;', 'lsaquo' => '&#8249;', 'rsaquo' => '&#8250;', 'euro' => '&#8364;', 'fnof' => '&#402;', 'Alpha' => '&#913;', 'Beta' => '&#914;', 'Gamma' => '&#915;', 'Delta' => '&#916;', 'Epsilon' => '&#917;',
    );
    if (isset($table[$matches[1]]))
        return $table[$matches[1]];
    // else
    return $destroy ? '' : $matches[0];
}

function autoCompileLess($inputFile, $outputFile) {

    $inputFile = $_SERVER['DOCUMENT_ROOT'] . $inputFile;
    $outputFile = $_SERVER['DOCUMENT_ROOT'] . $outputFile;

    if (!file_exists($inputFile)) {
        return false;
    }
    $cacheFile = $inputFile . ".cache";

    if (file_exists($cacheFile)) {

        $cache = unserialize(file_get_contents($cacheFile));
        // если изменился document root
        if ($cache['root'] !== $inputFile) {
            autoCompileLess($inputFile, $outputFile);
        }
    } else {

        $cache = $inputFile;
    }

    $less = new lessc();
    $newCache = $less->cachedCompile($cache);

    if (!is_array($cache) || ($newCache["updated"] > $cache["updated"])) {
        file_put_contents($cacheFile, serialize($newCache));
        file_put_contents($outputFile, $newCache['compiled']);
    }
}

function getDayByDate($timestamp) {
    $days = array(
        'воскресенье',
        'понедельник',
        'вторник',
        'среда',
        'четверг',
        'пятница',
        'суббота',
    );

    $day = (SITE_DIR == "/") ? strftime("%A", $timestamp) : $days[date('N', $timestamp)];
    return $day;
}

function parseYTurl($url) {
    $pattern = '/(?:https?:|\/\/)?(?:www\.)(?:youtu\.be\/|youtube\.com(?:\/embed\/|\/v\/|\/watch\?v=))([\w-]{10,12})/xui';
    preg_match($pattern, $url, $matches);
    return (isset($matches[0])) ? $matches[0] : false;
}

function getPagerCount() {
    return array(
        '8', '12', '16', '20', '24'
    );
}

function isMainPage() {
    global $APPLICATION;
    return ($APPLICATION->GetCurDir() == SITE_DIR) ? true : ($APPLICATION->GetCurDir() == '/pro/' ? true : false);
}

if (!function_exists('isMobilePhone')) {

    function isMobilePhone() {
        if (defined('IS_PHONE')) {
            return IS_PHONE;
        } else {
            return false;
        }
    }

}

function ifMobile($var) {
    return IS_MOBILE_SITE === 1 ? $var : false;
}

function setPageBack($picID) {
    if (!$picID) {
        return false;
    }
    $pic = CFile::GetPath($picID);
    if (file_exists($_SERVER['DOCUMENT_ROOT'] . $pic)) {
        setContent("page-back", "style='background-image:url({$pic})'");

        $img1600 = CFile::ResizeImageGet($picID, array('width' => 1600, 'height' => 8000), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true, array());

        $img1200 = CFile::ResizeImageGet($picID, array('width' => 1200, 'height' => 8000), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true, array());

        $adaptivePics = "data-change-bg data-1920='{$pic}' data-1600='{$img1600['src']}' data-1200='{$img1200['src']}'";

        setContent("page-back-adaptive", $adaptivePics);
    }
}

function isProSection() {
    global $APPLICATION;
    return strpos($APPLICATION->GetCurDir(), '/pro/') === 0;
}

function getCacheType() {
    return isAjax() ? "N" : "A";
}

function startAjaxHandler() {
    global $APPLICATION;
    if (isAjax()) {
        $APPLICATION->RestartBuffer();
        ob_start();
    }
}

function flushAjaxContent() {
    global $APPLICATION;
    if (isAjax()) {
        ob_end_flush();
        return true;
    }
    return false;
}

function getAjaxContent() {
    global $APPLICATION;
    if (isAjax()) {
        return ob_get_clean();
    }
    return false;
}

function ajaxInterrupt($result) {
    global $APPLICATION;
    if (isAjax()) {
        $APPLICATION->RestartBuffer();
        while (ob_get_level())
            ob_end_clean();
        $APPLICATION->EndBufferContent();
        if (!empty($result))
            ajax($result);
        exit();
    }
}

function ajax($var) {
    print_r(json_encode($var));
}

function sync_folder_files() {

    $files = array();
    $folders = func_get_args();

    if (empty($folders)) {
        return FALSE;
    }

    // Get all files
    foreach ($folders as $key => $folder) {
        // Normalise folder strings to remove trailing slash
        $folders[$key] = rtrim($folder, DIRECTORY_SEPARATOR);
        $files += glob($folder . DIRECTORY_SEPARATOR . '*');
    }

    // Drop same files
    $uniqueFiles = array();
    foreach ($files as $file) {
        $hash = md5_file($file);

        if (!in_array($hash, $uniqueFiles)) {
            $uniqueFiles[$file] = $hash;
        }
    }


    // Copy all these unique files into every folder
    foreach ($folders as $folder) {
        foreach ($uniqueFiles as $file => $hash) {
            copy($file, $folder . DIRECTORY_SEPARATOR . basename($file));
        }
    }
    return TRUE;
}

function make_image_pair($file_id, $ar_big_size, $ar_small_size) {
    $resultFiles = array();
    $arFile = CFile::GetFileArray($file_id);
    $arBigFile = CFile::ResizeImageGet($arFile, $ar_big_size, BX_RESIZE_IMAGE_PROPORTIONAL_ALT
    );
    $resultFiles['BIG_SRC'] = rel_path($arBigFile['src']);

    $arSmallFile = CFile::ResizeImageGet($arFile, $ar_small_size, BX_RESIZE_IMAGE_PROPORTIONAL_ALT
    );
    $resultFiles['SMALL_SRC'] = rel_path($arSmallFile['src']);

    jsDump($resultFiles);

    return $resultFiles;
}

function find_array_element(array $haystack, $key, $value) {
    foreach ($haystack as $haystack_key => $element) {
        if (is_array($element) && $element[$key] == $value) {
            return $haystack_key;
        }
    }
    return false;
}

function get_cur_sort_catalog() {
    $result = array();
    $cur_order = $_GET['order'];
    $cur_sort = $_GET['sort'];
    $result['cur_order'] = $cur_order;
    switch ($cur_order) {
        case 'asc':
            $result['order'] = '';
            break;
        case 'desc':
            $result['order'] = 'asc';
            break;
        default:
            $result['order'] = 'desc';
            break;
    }
    switch ($cur_sort) {
        case 'name':
            $result['sort']['name'] = 'active';
            $result['sort']['price'] = '';
            break;
        case 'price':
            $result['sort']['name'] = '';
            $result['sort']['price'] = 'active';
            break;
        default:
            $result['sort']['name'] = '';
            $result['sort']['price'] = '';
            break;
    }
    return $result;
}

function getHighLoadBlockByTable($table_name) {
    return $GLOBALS['DB']->Query('SELECT * FROM b_hlblock_entity WHERE `TABLE_NAME`=\'' . $table_name . '\'')->fetch();
}

function tick(&$i) {
    return ($i = abs($i - 1));
}

function prPart($arr, $keys) {
    foreach ($arr as $a) {
        $aprint = array();
        foreach ($keys as $k) {
            $aprint[] = $a[$k];
        }
        pr($aprint);
    }
}

function check_array($var) {
    return is_array($var) && count($var);
}

function getPropertyVariants($arParams, &$arDispProp) {
    $class = $arParams['PROPERTY_VARIANT_GETTERS'][$arDispProp['CODE']]['CLASS'];
    $method = $arParams['PROPERTY_VARIANT_GETTERS'][$arDispProp['CODE']]['METHOD'];
    if (method_exists($class, $method)) {
        return call_user_func(array($class, $method));
    }
    return false;
}

function print_if($string, $condition, $replace = false) {
    if ($condition)
        print($string);
    else
        print($replace);
}

function getNodeContentByID($html, $id) {
    $dom = new DOMDocument();
    @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'cp1251'));
    $node = $dom->getElementById($id);
    $nodeValue = $node->nodeValue;
    return $nodeValue;
}

function removeNodeByID($html, $id) {
    $dom = new DOMDocument;
    @$dom->loadHTML($html);
    $node = $dom->getElementById($id);
    $node->parentNode->removeChild($node);
    $res = html_entity_decode($dom->saveHTML());
    return $res;
}
