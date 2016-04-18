<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
$this->setFrameMode(true);
$APPLICATION->RestartBuffer();
ob_start();
?>
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <style type="text/css">
            <?=file_get_contents(__DIR__."/style.css")?>
        </style>
        <title><?=$arResult['ELEMENT']['NAME']?></title>
    </head>
    <body>

    <div class="inner">
        <div class="reg-kupon _event">
            <div class="title-holder">
                <div class="barcode">
                    Электронный билет
                    <?="<img src='data:image/png;base64,{$arResult["BARCODE"]}'>";?>
                    <span class="event-name"><?=$arResult['ELEMENT']['NAME']?></span>
                </div>
                <table>
                    <tr>
                        <td class="name">
                            <?=implode(" ", [$arResult['FORM_RESULT']['PROPERTIES']['NAME']['VALUE'],
                                             $arResult['FORM_RESULT']['PROPERTIES']['SURNAME']['VALUE'],
                                             $arResult['FORM_RESULT']['PROPERTIES']['LAST_NAME']['VALUE']])?>,
                            "<?=$arResult['FORM_RESULT']['PROPERTIES']['COMPANY']['VALUE']?>"
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="company">
                                <?=$arResult['ELEMENT']['PROPERTIES']['TICKET_TEXT']['VALUE']?>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <? if($arResult['ELEMENT']['PROPERTIES']['TICKET_IMAGE']['VALUE']) { ?>
                <img
                    src="http://<?=$_SERVER['SERVER_NAME']?><?=CFile::GetPath($arResult['ELEMENT']['PROPERTIES']['TICKET_IMAGE']['VALUE'])?>"
                    alt="">
            <? } ?>
            <? if(!isset($_GET['pdf'])) { ?>
                <a target="_blank" href="<?=$APPLICATION->GetCurUri()?>&pdf">Распечатать</a>
            <? } ?>
        </div>
    </div>
    </body>
    </html>
<?
$html = ob_get_clean();
if(isset($_GET['pdf'])) {
    define('DOMPDF_ENABLE_AUTOLOAD', false);
    define('DOMPDF_ENABLE_REMOTE', true);
    require $_SERVER['DOCUMENT_ROOT'].'/local/php_interface/include/vendor/autoload.php';
    $dompdf = new \DOMPDF();
    $dompdf->load_html($html);
    $dompdf->render();
    $dompdf->stream("ticket.pdf", array("Attachment" => 0));
}
else {
    echo $html;
}