<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>


<div class="row vertical-align">
    <div class="col-md-10">
        <h3><?= $arResult['NAME'] ?></h3>
        <div class="controls text-right">
            <? if (!empty($arResult["PREV"])): ?>
                <div class="prev">
                    <a href="<?= $arResult["PREV"] ?>">
                        <i class="fa fa-arrow-circle-o-left"></i>
                    </a>
                </div>
            <? endif; ?>
            <? if (!empty($arResult["NEXT"])): ?>
                <div class="next">
                    <a href="<?= $arResult["NEXT"] ?>">
                        <i class="fa fa-arrow-circle-o-right"></i>
                    </a>
                </div>
            <? endif; ?>
        </div>
    </div>
    <div class="col-md-2 text-right">
        <div class="hogart-share text-right">
            <a data-toggle="tooltip" data-placement="top" href="#" class="js-popup-open"
               data-popup="#popup-subscribe" title="<?= GetMessage("Отправить на e-mail") ?>"><i
                    class="fa fa-envelope" aria-hidden="true"></i></a>
            <a data-toggle="tooltip" data-placement="top" href="#" onclick="window.print(); return false;"
               title="<?= GetMessage("Распечатать") ?>"><i class="fa fa-print" aria-hidden="true"></i></a>
            <a data-toggle="tooltip" data-placement="top" href="#" class="js-popup-open"
               data-popup="#popup-subscribe-phone" title="<?= GetMessage("Отправить SMS") ?>"><i
                    class="fa fa-mobile" aria-hidden="true"></i></a>
        </div>
    </div>
</div>

<?=$arResult["DETAIL_TEXT"]?>
<?$share_img_src = $_SERVER['SERVER_NAME'].$arResult['DETAIL_PICTURE']['SRC'];?>
<?$APPLICATION->IncludeFile(
    "/local/include/share.php",
    array(
        "TITLE" => $arResult["NAME"],
        "DESCRIPTION" => !empty($arResult["PREVIEW_TEXT"]) ? $arResult["PREVIEW_TEXT"]:$arResult["DETAIL_TEXT"],
        "LINK" => $APPLICATION->GetCurPage(),
        "IMAGE"=> $share_img_src
    )
);?>
