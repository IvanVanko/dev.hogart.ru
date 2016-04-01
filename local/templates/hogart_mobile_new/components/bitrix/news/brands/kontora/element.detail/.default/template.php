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
    <? //var_dump($arResult['PREVIEW_PICTURE']) ?>
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
<!--<pre>--><?//var_dump($arResult['PRODUCTS'])?><!--</pre>-->
    <? if (!empty($arResult['PARENT_SECTIONS']) && !empty($arResult['PRODUCT_SECTION_GROUPS']) && !empty($arResult['PRODUCT_GROUPS'])): ?>

        <div class="category_list_box" style="display: none;">


        <p><a class="view-more" href="#view-more">Читать далее</a></p>

        <h2>Каталог продукции <?= $arResult['NAME'] ?></h2>

        <div class="fixheight"></div>

        <ul class="category_list">
            <?jsDump(array($arResult['PARENT_SECTIONS'],$arResult['PRODUCT_SECTION_GROUPS'], $arResult['PRODUCT_GROUPS']));?>
            <?foreach ($arResult['PARENT_SECTIONS'] as $arParentSection) {
                $s_id = $arParentSection['ID']?>
                <li>
                    <? if (!empty($productSection['PICTURE'])): ?>
                        <img src="<?= CFile::GetPath($arParentSection['PICTURE']) ?>" alt=""/>
                    <?else:?>
                        <img src="/images/no-img.jpg" alt=""/>
                    <? endif; ?>
                    <h2><a href="<?= $arParentSection['SECTION_PAGE_URL'] ?>"><?= $arParentSection['NAME'] ?></a></h2>
                    <ul>
                        <? foreach ($arResult['PRODUCT_SECTION_GROUPS'][$s_id] as $arChildSection):
                            $ch_id = $arChildSection['ID']?>

                            <li><a href="<?= $arChildSection['SECTION_PAGE_URL'] ?>"><?= $arChildSection['NAME'] ?></a> (<?= $arResult['PRODUCT_GROUPS'][$ch_id]['CNT'] ?>)</li>
                        <? endforeach; ?>
                    </ul>
                </li>
            <?}?>
            <?/* foreach ($arResult['PRODUCTS'] as $key => $productSection): ?>
                <?if (!empty($productSection['SECTIONS'])):?>

<!--                    --><?//count($productSection['SECTIONS']['COUNT_PRODS']);?>
                <?if ($productSection['COUNT_PRODS'] > 0):?>

                <li count="<?=$productSection['COUNT_PRODS'];?>" >
                    <? if (!empty($productSection['PICTURE'])): ?>
                        <img src="<?= CFile::GetPath($productSection['PICTURE']) ?>" alt=""/>
                    <?else:?>
                        <img src="/images/no-img.jpg" alt=""/>
                    <? endif; ?>

                    <h2><a href="<?= $productSection['SECTION_PAGE_URL'] ?>"><?= $productSection['NAME'] ?></a></h2>
                    <? if (!empty($productSection['SECTIONS'])): ?>
                        <ul>
                            <? foreach ($productSection['SECTIONS'] as $arSection): ?>

                                <li><a href="<?= $arSection['SECTION_PAGE_URL'] ?>"><?= $arSection['NAME'] ?></a> (<?= $arSection['COUNT_PRODS'] ?>)</li>
                            <? endforeach; ?>
                        </ul>
                    <? endif; ?>
                </li>
            <?endif;?>
            <?endif;?>
            <? endforeach; */?>
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

                <?foreach ($arResult["PROPERTIES"]["photogallery"]["VALUE"] as $key => $picId):
//                    $file = CFile::ResizeImageGet($picId, array('width'=>200, 'height'=>150), BX_RESIZE_IMAGE_EXACT, true);
//                    $file = CFile::ResizeImageGet($picId, array('width'=>328, 'height'=>270), BX_RESIZE_IMAGE_PROPORTIONAL, true);
//                    $file = CFile::ResizeImageGet($picId, array('width'=>480, 'height'=>360), BX_RESIZE_IMAGE_EXACT, true);
                    $file = CFile::ResizeImageGet($picId, array('width'=>320, 'height'=>180), BX_RESIZE_IMAGE_EXACT, true);
                    $fileBig = CFile::ResizeImageGet($picId, array('width'=>800, 'height'=>600), BX_RESIZE_IMAGE_EXACT, true);
                    ?>
                    <li>
                        <div class="img-wrap">
                            <img class="js-popup-open-img"src="<?= $file['src']; ?>" data-group="gallG" data-big-img="<?= $fileBig['src']; ?>" alt=""/>
                        </div>
                    </li>

                    <?if(!empty($arResult["PROPERTIES"]["videogallery"]['VALUE'][$key])):?>
                    <li>
                        <div class="img-wrap video">
                            <img class="js-popup-open-img" data-big-video="<?=$arResult["PROPERTIES"]["videogallery"]['VALUE'][$key]?>" data-group="gallG" src="http://img.youtube.com/vi/<?=$arResult["PROPERTIES"]["videogallery"]['VALUE'][$key]?>/mqdefault.jpg.jpg"  alt=""/>
                           <!-- <img class="js-popup-open-img" data-big-video="<?/*=$arResult["PROPERTIES"]["videogallery"]['VALUE'][$key]*/?>" data-group="gallG" src="http://img.youtube.com/vi/<?/*=$arResult["PROPERTIES"]["videogallery"]['VALUE'][$key]*/?>/0.jpg"  alt=""/>-->
                        </div>
                    </li>
                <?endif;?>
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
                        /*$('.js-service-slider li').each(function () {
                            var liH = $(this).height();
                            $(this).attr('data-h', liH);
                            if(liH/360 < 1.5){
                                $('.js-service-slider').parent().height(liH);
                            }
                            console.log(liH);
                        });*/
                    }, 100);
                }
            </script>
        <?else:?>
            <ul class="gall-list">
                <?foreach ($arResult["PROPERTIES"]["photogallery"]["VALUE"] as $key => $picId):
//                    $file = CFile::ResizeImageGet($picId, array('width'=>328, 'height'=>270), BX_RESIZE_IMAGE_PROPORTIONAL, true);
//                    $file = CFile::ResizeImageGet($picId, array('width'=>436, 'height'=>270), BX_RESIZE_IMAGE_EXACT, true);
                    $file = CFile::ResizeImageGet($picId, array('width'=>200, 'height'=>135), BX_RESIZE_IMAGE_EXACT, true);
                    $fileBig = CFile::ResizeImageGet($picId, array('width'=>800, 'height'=>600), BX_RESIZE_IMAGE_EXACT, true);
                    ?>
                    <li>
                        <div class="img-wrap">
                            <img class="js-popup-open-img"src="<?= $file['src']; ?>" data-group="gallG" data-big-img="<?= $fileBig['src']; ?>" alt=""/>
                        </div>
                    </li>

                    <?if(!empty($arResult["PROPERTIES"]["videogallery"]['VALUE'][$key])):?>
                    <li>
                        <div class="img-wrap video">
                            <img class="js-popup-open-img" data-big-video="<?=$arResult["PROPERTIES"]["videogallery"]['VALUE'][$key]?>" src="http://img.youtube.com/vi/<?=$arResult["PROPERTIES"]["videogallery"]['VALUE'][$key]?>/mqdefault.jpg"  alt=""/>
                        </div>
                    </li>
                <?endif;?>
                <?endforeach?>
            </ul>
            <br/><br/>
        <?endif;?>
    <?endif;?>

    <?/* if (!empty($arResult['PROPERTIES']['photogallery']['VALUE']) || !empty($arResult['PROPERTIES']['videogallery']['VALUE'])): ?>
        <ul class="images-slider js-normal-slider" data-next=".js-control-cnt .next" data-prev=".js-control-cnt .prev">
            <?foreach ($arResult['PROPERTIES']['photogallery']['VALUE'] as $key => $picture):
                $file = CFile::ResizeImageGet($picture, array('width' => 699, 'height' => 362), BX_RESIZE_IMAGE_EXACT, true);      ?>
                <li>
                    <div class="img-wrap">
                        <img class="js-popup-open-img" data-group="gallG" src="<?= $file['src'] ?>" alt=""/>
                    </div>
                </li>
                <? if (!empty($arResult['PROPERTIES']['videogallery']['VALUE'])): ?>
                <? foreach ($arResult['PROPERTIES']['videogallery']['VALUE'] as $key => $picture): ?>
                    <li>
                        <div class="video-item big js-popup-open-video">
                            <iframe width="100%" height="100%"
                                    src="https://www.youtube.com/embed/<?= $picture;?>?rel=0&amp;controls=0"
                                    frameborder="0" allowfullscreen></iframe>
                            <img src="http://img.youtube.com/vi/<?= $picture;?>/maxresdefault.jpg" alt="">
                        </div>
                    </li>
                <? endforeach; ?>
            <? endif; ?>
            <? endforeach; ?>

        </ul>
    <? endif; ?>
    <? if (!empty($arResult['PROPERTIES']['photogallery']['VALUE']) || !empty($arResult['PROPERTIES']['videogallery']['VALUE'])): ?>
        <div class="control js-control-cnt">
            <span class="prev black"></span>
            <span class="next black"></span>
        </div>
    <? endif; */?>
    <div>
        <?= $arResult['DETAIL_TEXT'] ?>
    </div>





</div>