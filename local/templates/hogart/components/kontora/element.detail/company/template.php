<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
?>

<div class="row company">
    <div class="col-md-9">
        <h3 style="margin-top: 10px"><?= $arResult['NAME'] ?></h3>
        <div class="preview-text">
            <?= $arResult["PREVIEW_TEXT"] ?>
        </div>
        <div class="detail-text">
            <?= $arResult["DETAIL_TEXT"] ?>
        </div>

        <? if (!empty($arResult['PROPERTIES']['points']['VALUE'])): ?>
            <ul class="counter-company row">
                <? foreach ($arResult['PROPERTIES']['points']['VALUE'] as $key => $value): ?>
                    <li class="col-md-12">
                        <span><?= $key + 1 ?></span>
                        <p><?= $value ?></p>
                    </li>
                <? endforeach; ?>
            </ul>
        <? endif; ?>

        <? if (!empty($arResult['PROPERTIES']['infographics']['VALUE'])): ?>
            <h4><?= $arResult['PROPERTIES']['infographics']["NAME"] ?></h4>
            <? if (count($arResult["PROPERTIES"]["infographics"]["VALUE"]) > 6): ?>
                <div id="js-control-infographics" class="controls text-right">
                    <span class="prev"></span>
                    <span class="next"></span>
                </div>
                <ul class="sert-slider-cnt js-infographics-slider">
                    <? foreach ($arResult["PROPERTIES"]["infographics"]["VALUE"] as $value):
                        $file = CFile::ResizeImageGet($value, array('width' => 126, 'height' => 179), BX_RESIZE_IMAGE_EXACT, true);
                        $fileBig = CFile::GetPath($value);
                        ?>
                        <li><img src="<?= $file['src'] ?>" data-group="gallG" data-big-img="<?= $fileBig ?>"
                                 class="js-popup-open-img" alt=""/></li>
                    <? endforeach ?>
                </ul>

            <script>
                $(function () {
                    $('.js-infographics-slider').bxSlider({
                        minSlides: 6,
                        maxSlides: 6,
                        slideMargin: 22,
                        slideWidth: $(this).width() / 6 - 22,
                        pager: false,
                        nextText: '',
                        prevText: '',
                        nextSelector: $('#js-control-infographics').find('.next'),
                        prevSelector: $('#js-control-infographics').find('.prev'),
                        infiniteLoop: false
                    });
                });
            </script>
            <? endif; ?>
        <? endif; ?>

        <h3><?=GetMessage("Хогарт сегодня")?></h3>
        <? $APPLICATION->IncludeComponent("kontora:element.list", "hogart_today", array(
            "IBLOCK_ID" => "21",
            "PROPS" => "Y",
            'ORDER' => array('sort' => 'asc'),
        )); ?>

        <? if (!empty($arResult["PROPERTIES"]["honors"]["VALUE"])): ?>
            <div class="inner">
                <h3><?=GetMessage("Достижения и награды")?></h3>
                <? if (count($arResult["PROPERTIES"]["honors"]["VALUE"]) > 6): ?>
                    <div id="js-control-company" class="controls text-right">
                        <span class="prev"></span>
                        <span class="next"></span>
                    </div>
                <? endif; ?>
                <ul class="sert-slider-cnt js-company-slider">
                    <? foreach ($arResult["PROPERTIES"]["honors"]["VALUE"] as $value):
                        $file = CFile::ResizeImageGet($value, array('width' => 126, 'height' => 179), BX_RESIZE_IMAGE_EXACT, true);
                        $fileBig = CFile::GetPath($value);
                        ?>
                        <li><img src="<?= $file['src'] ?>" data-group="gallG" data-big-img="<?= $fileBig ?>"
                                 class="js-popup-open-img" alt=""/></li>
                    <? endforeach ?>
                </ul>
            </div>
        <? endif; ?>

        <?$APPLICATION->IncludeFile(
            "/local/include/share.php",
            array(
                "TITLE" => $arResult["NAME"],
                "DESCRIPTION" => !empty($arResult["PREVIEW_TEXT"]) ? $arResult["PREVIEW_TEXT"]:$arResult["DETAIL_TEXT"],
                "LINK" => $APPLICATION->GetCurPage(),
                "IMAGE"=> $share_img_src
            )
        );?>
        
    </div>
    <div class="col-md-3 aside">
        <? $APPLICATION->IncludeComponent("bitrix:menu", "section_menu", Array(
                "ROOT_MENU_TYPE" => "left",
                "MAX_LEVEL" => "1",
                "CHILD_MENU_TYPE" => "left",
                "USE_EXT" => "Y",
                "DELAY" => "N",
                "ALLOW_MULTI_SELECT" => "Y",
                "MENU_CACHE_TYPE" => "N",
                "MENU_CACHE_TIME" => "3600",
                "MENU_CACHE_USE_GROUPS" => "Y",
                "MENU_CACHE_GET_VARS" => ""
            )
        );
        ?>
    </div>
</div>

