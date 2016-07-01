<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<div class="row vertical-align">
    <div class="col-md-11">
        <h3 style="margin-top: 10px"><?= $arResult['NAME'] ?></h3>
    </div>
    <div class="col-md-1">
        <div id="con-4" class="controls text-right">
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
        <? $videogallery = $arResult['PROPERTIES']['videogallery']['VALUE'] ?>
        <?$galleries = array_merge($arResult["PROPERTIES"]["photogallery"]['VALUE'], $arResult["PROPERTIES"]["videogallery"]['VALUE']);?>
        <?if (!empty($arResult["PROPERTIES"]["photogallery"]['VALUE']) || !empty($arResult["PROPERTIES"]["videogallery"]['VALUE'])):?>
            <?if (count($arResult["PROPERTIES"]["photogallery"]['VALUE'])>3):?>
                <div id="js-service-slider" class="controls text-right">
                    <div class="prev"></div>
                    <div class="next"></div>
                </div>
                <ul class="sert-slider-cnt js-service-slider">
                    <?foreach ($arResult["PROPERTIES"]["videogallery"]["VALUE"] as $key => $picId) {?>
                        <li>
                            <div class="img-wrap video">
                                <img class="js-popup-open-img" title="<?=$arResult["NAME"]?>" data-big-video="<?=$arResult["PROPERTIES"]["videogallery"]['VALUE'][$key]?>" src="http://img.youtube.com/vi/<?=$arResult["PROPERTIES"]["videogallery"]['VALUE'][$key]?>/mqdefault.jpg"  alt=""/>
                            </div>
                        </li>
                    <?}?>
                    <?foreach ($arResult["PROPERTIES"]["photogallery"]["VALUE"] as $key => $picId):
                        $file = CFile::ResizeImageGet($picId, array('width'=>320, 'height'=>180), BX_RESIZE_IMAGE_EXACT, true);
                        $fileBig = CFile::ResizeImageGet($picId, array('width'=>800, 'height'=>600), BX_RESIZE_IMAGE_EXACT, true);
                        ?>
                        <li>
                            <div class="img-wrap">
                                <img class="js-popup-open-img"src="<?= $file['src']; ?>" title="<?=$arResult["NAME"]?>" data-group="gallG" data-big-img="<?= $fileBig['src']; ?>" alt=""/>
                            </div>
                        </li>
                    <?endforeach?>
                </ul>
            <?else:?>
                <ul class="gall-list">
                    <?foreach ($arResult["PROPERTIES"]["videogallery"]["VALUE"] as $key => $picId) {?>
                        <li>
                            <div class="img-wrap video">
                                <img class="js-popup-open-img" title="<?=$arResult["NAME"]?>" data-big-video="<?=$arResult["PROPERTIES"]["videogallery"]['VALUE'][$key]?>" src="http://img.youtube.com/vi/<?=$arResult["PROPERTIES"]["videogallery"]['VALUE'][$key]?>/mqdefault.jpg"  alt=""/>
                            </div>
                        </li>
                    <?}?>
                    <?foreach ($arResult["PROPERTIES"]["photogallery"]["VALUE"] as $key => $picId):
                        $file = CFile::ResizeImageGet($picId, array('width'=>200, 'height'=>135), BX_RESIZE_IMAGE_EXACT, true);
                        $fileBig = CFile::ResizeImageGet($picId, array('width'=>800, 'height'=>600), BX_RESIZE_IMAGE_EXACT, true);
                        ?>
                        <li>
                            <div class="img-wrap">
                                <img class="js-popup-open-img"src="<?= $file['src']; ?>" title="<?=$arResult["NAME"]?>" data-group="gallG" data-big-img="<?= $fileBig['src']; ?>" alt=""/>
                            </div>
                        </li>

                    <?endforeach?>
                </ul>
            <?endif;?>
            <script>
                $(function () {
                    $('.js-service-slider').bxSlider({
                        minSlides: 3,
                        maxSlides: 3,
                        slideMargin: 22,
                        slideWidth: $(this).width() / 3 - 22,
                        pager: false,
                        nextText: '<i class="fa fa-arrow-circle-o-right"></i>',
                        prevText: '<i class="fa fa-arrow-circle-o-left"></i>',
                        nextSelector: $('#js-service-slider').find('.next'),
                        prevSelector: $('#js-service-slider').find('.prev'),
                        infiniteLoop: false
                    });
                });
            </script>
        <?endif;?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="collapse" id="collapseDetail">
            <?= $arResult['DETAIL_TEXT'] ?>
        </div>
        <? if (!empty($arResult['DETAIL_TEXT'])): ?>
        <a data-toggle="collapse" href="#collapseDetail" aria-expanded="false" aria-controls="collapseDetail">Читать далее</a>
        <? endif; ?>
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