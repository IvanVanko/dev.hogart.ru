<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
if (intval($_GET['id'])) {
    $obFile = new CFile();
    $file = $obFile->GetByID($_GET['id'])->GetNext();
    $path = CFile::GetPath($_GET['id']);
    $type = mime_content_type($_SERVER['DOCUMENT_ROOT']."$path");
    if (!empty($_REQUEST['name'])) {
        $name = $_REQUEST['name'];
    } else {
        $name = basename($file['ORIGINAL_NAME']);
    }
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0, max-age=0");
    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header("Content-type: application/octet-stream");
    header("Content-Transfer-Encoding: binary");
    header("Content-Disposition: attachment; filename=".$name);
//    if ($zip_type = checkOfficeMime($type)) {
//        $type = $zip_type;
//    }
//    header("Content-Type: ".$type);
    readfile($_SERVER['DOCUMENT_ROOT'].$path);
} else {
    header("HTTP/1.0 404 Not Found");
}
