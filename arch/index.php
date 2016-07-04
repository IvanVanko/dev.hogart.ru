<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

if(!isset($_REQUEST['qzip'])) {
    LocalRedirect($_SERVER['HTTP_REFERER']);
}
$qzip = $_REQUEST['qzip'];
$qzip = array_filter(explode(',', $_REQUEST['qzip']));
if(empty($qzip)) {
    LocalRedirect($_SERVER['HTTP_REFERER']);
}

$zip = new ZipArchive();

$zip_name = 'hogart_docs'.time().'.zip';
$file_folder = '/upload/';

$a_file = $_SERVER["DOCUMENT_ROOT"].$file_folder.$zip_name;

$zip->open($a_file, ZipArchive::CREATE);

foreach($qzip as $file) {
    $ci__documentation__element = CIBlockElement::GetList(
        array(),
        array("ID" => $file, "IBLOCK" => 10),
        false,
        false,
        array("PROPERTY_FILE", "NAME")
    );
    $documentation__item = $ci__documentation__element->GetNext();

    $file = $documentation__item['PROPERTY_FILE_VALUE'];
    $rsFile = CFile::GetByID($file);
    $arFile = $rsFile->Fetch();

    $document__extension = explode(".", $arFile['ORIGINAL_NAME']);
    $document__extension = $document__extension[1];

    $documentation__name__cp862 = CUtil::translit($documentation__item['NAME'], "ru").".".$document__extension;
    $filePath = $_SERVER["DOCUMENT_ROOT"].CFile::GetPath($file);
    $zip->addFile($filePath, $documentation__name__cp862);
}

$zip->close();

if(file_exists($a_file)) {
    header('Content-type: application/zip');
    header('Content-Disposition: attachment; filename='.$zip_name);
    readfile($a_file);
    unlink($a_file);
}
