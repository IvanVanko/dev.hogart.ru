<?
if ($_REQUEST['file'] && $_REQUEST['email']) {
    $file = $_REQUEST['file'];
    $dir = __DIR__;
    if (is_readable($dir."/".$file)) {
        $content = file_get_contents($dir."/".$file);
        print_r(array($_REQUEST['email'], "test mail", $content));
        mail($_REQUEST['email'], "test mail", $content, "Content-type: text/html; charset=utf-8");
    }
}
?>