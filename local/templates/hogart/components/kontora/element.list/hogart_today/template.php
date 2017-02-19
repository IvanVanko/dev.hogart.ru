<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? if (count($arResult['ITEMS']) > 0): ?>

    <? if (count($arResult["ITEMS"]) > 3):?>
        <div id="js-hogart-today-slider" class="controls text-right">
            <div class="prev"><i class="fa fa-arrow-circle-o-left"></i></div>
            <div class="next"><i class="fa fa-arrow-circle-o-right"></i></div>
        </div>
    <? endif; ?>
    <? if (count($arResult["ITEMS"]) > 1):?>
        <div id="js-hogart-today-slider-mobile" class="controls controls-mobile text-right">
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
<!--     <script>
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
</script> -->
    <script>
        $(document).ready(function () {
            // initiates responsive slide gallery           
            var settings = function() {
                var settings1 = {
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
                };
                var settings2 = {
                    minSlides: 1,
                    maxSlides: 1,
                    infiniteLoop: true,
                    pager: true,
                    pagerType: 'full',
                    slideWidth: $(this).width() / 1,
                    nextText: '',
                    prevText: '',
                    nextSelector: $('#js-hogart-today-slider-mobile').find('.next'),
                    prevSelector: $('#js-hogart-today-slider-mobile').find('.prev')
                };
                return ($(window).width()<768) ? settings2 : settings1;
            }

            var mySlider;

            function tourLandingScript() {
                mySlider.reloadSlider(settings());
            }

            mySlider = $('.js-hogart-today-slider').bxSlider(settings());
            $(window).resize(tourLandingScript);
        });
    </script>
<? endif; ?>