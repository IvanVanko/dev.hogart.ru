<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? if (count($arResult['ITEMS']) > 0): ?>

    <? if (count($arResult["ITEMS"]) > 3):?>
        <div id="js-hogart-today-slider" class="controls text-right">
            <div class="prev"><i class="fa fa-arrow-circle-o-left"></i></div>
            <div class="next"><i class="fa fa-arrow-circle-o-right"></i></div>
        </div>
    <? endif; ?>

    <div class="video-block">
        <ul class="sert-slider-cnt js-hogart-today-slider">
            <? foreach ($arResult['ITEMS'] as $key => $arItem): ?>
                <li>
                    <div class="img-wrap video">
                        <img class="js-popup-open-img" title="<?=$arItem["NAME"]?>" data-big-video="<?= $arItem["PROPERTIES"]['video']['VALUE'] ?>" src="http://img.youtube.com/vi/<?= $arItem["PROPERTIES"]['video']['VALUE'] ?>/mqdefault.jpg"  alt=""/>
                    </div>
                </li>
            <? endforeach; ?>
        </ul>
    </div>
    <script>
        $(function () {
            $('.js-hogart-today-slider').bxSlider({
                minSlides: 3,
                maxSlides: 3,
                slideMargin: 22,
                slideWidth: $(this).width() / 3 - 22,
                pager: false,
                nextText: '',
                prevText: '',
                nextSelector: $('#js-hogart-today-slider').find('.next'),
                prevSelector: $('#js-hogart-today-slider').find('.prev'),
                infiniteLoop: false
            });
        });
    </script>
<? endif; ?>