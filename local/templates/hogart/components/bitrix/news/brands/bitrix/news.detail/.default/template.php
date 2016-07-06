<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<div class="row vertical-align">
    <div class="col-md-10">
        <h3 style="margin-top: 10px"><?= $arResult['NAME'] ?></h3>
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

<div class="row">
    <div class="col-md-3">
        <?
        if (!empty($arResult['PREVIEW_PICTURE']['SRC']) && file_exists($_SERVER["DOCUMENT_ROOT"] . $arResult['PREVIEW_PICTURE']['SRC'])) {
            $arImage = $arResult['PREVIEW_PICTURE'];
        }
        $arSize = array("width" => 436, "height" => 270); 
        if (!empty($arImage)) {
            $file = CFile::ResizeImageGet($arImage, $arSize, BX_RESIZE_IMAGE_PROPORTIONAL, true);
            $pic = $file['src'];
        } else {
            $pic = "/images/project_no_img.jpg";
        }
        ?>
        <img class="brand-photo-thing" src="<?= $pic ?>" alt=""/ >
        <a target="_blank" href="<?= $arResult['PROPERTIES']['site']['VALUE'] ?>"><?= $arResult['PROPERTIES']['site']['VALUE'] ?></a>
    </div>
    <div class="col-md-9">
        <?= $arResult['PREVIEW_TEXT'] ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <? if (!empty($arResult["PROPERTIES"]["photogallery"]['VALUE'])): ?>
            <? if (count($arResult["PROPERTIES"]["photogallery"]['VALUE']) > 3):?>
                <div id="js-service-slider-photo" class="controls text-right">
                    <div class="prev"><i class="fa fa-arrow-circle-o-left"></i></div>
                    <div class="next"><i class="fa fa-arrow-circle-o-right"></i></div>
                </div>
            <? endif; ?>
                <ul class="sert-slider-cnt js-service-slider-photo">
                    <?foreach ($arResult["PROPERTIES"]["photogallery"]["VALUE"] as $key => $picId):
                        $file = CFile::ResizeImageGet($picId, array('width'=>320, 'height'=>180), BX_RESIZE_IMAGE_EXACT, true);
                        $fileBig = CFile::ResizeImageGet($picId, array('width'=>800, 'height'=>600), BX_RESIZE_IMAGE_EXACT, true);
                        ?>
                        <li>
                            <div class="img-wrap">
                                <img class="js-popup-open-img" src="<?= $file['src']; ?>" title="<?=$arResult["NAME"]?>" data-group="gallG" data-big-img="<?= $fileBig['src']; ?>" alt=""/>
                            </div>
                        </li>
                    <?endforeach?>
                </ul>
            <script>
                $(function () {
                    $('.js-service-slider-photo').bxSlider({
                        minSlides: 3,
                        maxSlides: 3,
                        slideMargin: 22,
                        slideWidth: $(this).width() / 3 - 22,
                        pager: false,
                        nextText: '',
                        prevText: '',
                        nextSelector: $('#js-service-slider-photo').find('.next'),
                        prevSelector: $('#js-service-slider-photo').find('.prev'),
                        infiniteLoop: false
                    });
                });
            </script>
        <? endif; ?>

        <? if (!empty($arResult["PROPERTIES"]["videogallery"]['VALUE'])): ?>
            <? if (count($arResult["PROPERTIES"]["videogallery"]['VALUE']) > 3):?>
                <div id="js-service-slider-video" class="controls text-right">
                    <div class="prev"><i class="fa fa-arrow-circle-o-left"></i></div>
                    <div class="next"><i class="fa fa-arrow-circle-o-right"></i></div>
                </div>
            <? endif; ?>
            <ul class="sert-slider-cnt js-service-slider-video">
                <? foreach ($arResult["PROPERTIES"]["videogallery"]["VALUE"] as $key => $picId): ?>
                    <li>
                        <div class="img-wrap video">
                            <img class="js-popup-open-img" title="<?=$arResult["NAME"]?>" data-big-video="<?=$arResult["PROPERTIES"]["videogallery"]['VALUE'][$key]?>" src="http://img.youtube.com/vi/<?=$arResult["PROPERTIES"]["videogallery"]['VALUE'][$key]?>/mqdefault.jpg"  alt=""/>
                        </div>
                    </li>
                <? endforeach; ?>
            </ul>
            <script>
                $(function () {
                    $('.js-service-slider-video').bxSlider({
                        minSlides: 3,
                        maxSlides: 3,
                        slideMargin: 22,
                        slideWidth: $(this).width() / 3 - 22,
                        pager: false,
                        nextText: '',
                        prevText: '',
                        nextSelector: $('#js-service-slider-video').find('.next'),
                        prevSelector: $('#js-service-slider-video').find('.prev'),
                        infiniteLoop: false
                    });
                });
            </script>
        <? endif; ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?= $arResult['DETAIL_TEXT'] ?>
    </div>
</div>

<? if (!empty($arResult['PARENT_SECTIONS']) && !empty($arResult['PRODUCT_SECTION_GROUPS']) && !empty($arResult['PRODUCT_GROUPS'])): ?>
    <? $this->SetViewTarget('brand-catalog') ?>
    <div class="brand-catalog">
        <div class="title h4 text-uppercase">Каталог продукции <span class="brand-name"><?= $arResult['NAME'] ?></span></div>
        <ul>
            <? foreach ($arResult['PARENT_SECTIONS'] as $arParentSection): ?>
                <li>
                    <a class="h4 text-uppercase" href="<?= $arParentSection['SECTION_PAGE_URL'] ?>"><?= $arParentSection['NAME'] ?></a>
                    <ul>
                        <? foreach ($arResult['PRODUCT_SECTION_GROUPS'][$arParentSection['ID']] as $arChildSection): ?>
                            <? $ch_id = $arChildSection['ID'] ?>

                            <li><a class="h5" href="<?= $arChildSection['SECTION_PAGE_URL'] ?>"><?= $arChildSection['NAME'] ?></a>
                                (<?= $arResult['PRODUCT_GROUPS'][$ch_id]['CNT'] ?>)
                            </li>
                        <? endforeach; ?>
                    </ul>
                </li>
            <? endforeach; ?>
        </ul>
    </div>
    <? $this->EndViewTarget() ?>
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