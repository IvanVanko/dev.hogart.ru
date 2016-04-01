<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<div class="inner">

    <div class="control control-action">

        <? /* if (isset($arResult['PREV'])):?>
                <span class="prev black"><a href="<?=$arResult['PREV']?>"></a></span>
            <?endif;?>
            <?if (isset($arResult['NEXT'])):?>
                <span class="next black"><a href="<?=$arResult['NEXT']?>"></a></span>
            <?endif; */ ?>
        <span class="prev <?if (isset($arResult['PREV'])):?> black <?endif;?>"><a href="<?=$arResult['PREV']?>"></a></span>
        <span class="next <?if (isset($arResult['NEXT'])):?> black <?endif;?>"><a href="<?=$arResult['NEXT']?>"></a></span>
    </div>
    <h1><?= $arResult['NAME'] ?></h1>
</div>
<div class="brand-site inner">
    <?
    $brandImg = CFile::ResizeImageGet($arResult['PREVIEW_PICTURE']['ID'], array('width'=>436, 'height'=>270), BX_RESIZE_IMAGE_PROPORTIONAL, true);
    ?>
    <img class="brand-photo-thing" src="<?= $brandImg['src'] ?>" alt=""/ >
    <a target="_blank" class="js-vertical-center"
       href="<?= $arResult['PROPERTIES']['site']['VALUE'] ?>"><?= $arResult['PROPERTIES']['site']['VALUE'] ?></a>
</div>
<div class="inner">
<div>
    <?= $arResult['PREVIEW_TEXT'] ?>
</div>
<? if (!empty($arResult['PARENT_SECTIONS']) && !empty($arResult['PRODUCT_SECTION_GROUPS']) && !empty($arResult['PRODUCT_GROUPS'])): ?>

    <div class="category_list_box" style="display: none;">


        <p><a class="view-more" href="#view-more">Читать далее</a></p>

        <h2>Каталог продукции <?= $arResult['NAME'] ?></h2>

        <div class="fixheight"></div>

        <ul class="category_list">
            <?foreach ($arResult['PARENT_SECTIONS'] as $arParentSection) {
                $file = CFile::ResizeImageGet($arParentSection['PICTURE'], array('width'=>101, 'height'=>150), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                $s_id = $arParentSection['ID']?>
                <li>
                    <? if ($file['src']): ?>
                        <img src="<?= $file['src']; ?>" alt=""/>
                    <? else: ?>
                        <img src="/images/no-img.jpg" alt=""/>
                    <? endif; ?>
                    <h2><a href="<?= $arParentSection['SECTION_PAGE_URL'] ?>"><?= $arParentSection['NAME'] ?></a></h2>
                    <ul>
                        <? foreach ($arResult['PRODUCT_SECTION_GROUPS'][$s_id] as $arChildSection):
                            $ch_id = $arChildSection['ID']?>

                            <li><a href="<?= $arChildSection['SECTION_PAGE_URL'] ?>"><?= $arChildSection['NAME'] ?></a>
                                (<?= $arResult['PRODUCT_GROUPS'][$ch_id]['CNT'] ?>)
                            </li>
                        <? endforeach; ?>
                    </ul>
                </li>
            <? } ?>
        </ul>
    </div>
    <script type="text/javascript">
        if ($('.category_list > li').length>0){

            $('.category_list_box').show();
        }
    </script>
<? endif; ?>
<a name="view-more"></a>

<? $videogallery = $arResult['PROPERTIES']['videogallery']['VALUE'] ?>
<?$galleries = array_merge($arResult["PROPERTIES"]["photogallery"]['VALUE'], $arResult["PROPERTIES"]["videogallery"]['VALUE']);?>
<?if (!empty($arResult["PROPERTIES"]["photogallery"]['VALUE']) || !empty($arResult["PROPERTIES"]["videogallery"]['VALUE'])):?>
    <?if (count($arResult["PROPERTIES"]["photogallery"]['VALUE'])>3):?>

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
        <?if (count($arResult["PROPERTIES"]["photogallery"]['VALUE'])>3):?>
        <div id="js-service-slider" class="control">
            <span class="prev black"></span>
            <span class="next black"></span>
        </div>
    <?else:?>
    <br/>
    <?endif;?>
        <script type="text/javascript">
            if ($('.js-service-slider').length) {
                setTimeout(function () {
                    $('.js-service-slider').bxSlider({
                        minSlides: 3,
                        maxSlides: 3,
                        slideMargin: 22,
                        slideWidth: $(this).width() / 3 - 22,
                        pager: false,
                        nextText: '',
                        prevText: '',
                        nextSelector: $('#js-service-slider').find('.next'),
                        prevSelector: $('#js-service-slider').find('.prev'),
                        infiniteLoop: false
                    });
                    $('.js-service-slider li').each(function () {
                     var liH = $(this).height();
                     $(this).attr('data-h', liH);
                     if(liH/360 < 1.5){
                     $('.js-service-slider').parent().height(liH);
                     }
                     console.log(liH);
                     });
                }, 100);
            }
        </script>
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
        <br/><br/>
    <?endif;?>
<?endif;?>

<div>
    <?= $arResult['DETAIL_TEXT'] ?>
</div>



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