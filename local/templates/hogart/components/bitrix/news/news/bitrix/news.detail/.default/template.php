<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$date_from = FormatDate("d F Y", MakeTimeStamp($arResult["ACTIVE_FROM"]));
$share_img_src = false;
?>
<div class="row">
    <div class="col-md-9 col-xs-12">
        <div class="row vertical-align">
            <div class="col-md-10">
                <h3 style="margin-top: 10px"><?$APPLICATION->ShowTitle()?></h3>
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
                    <a data-toggle="tooltip" data-placement="top" href="#" class="js-popup-open" data-popup="#popup-subscribe" title="<?= GetMessage("Отправить на e-mail")?>"><i class="fa fa-envelope" aria-hidden="true"></i></a>
                    <a data-toggle="tooltip" data-placement="top" href="#" onclick="window.print(); return false;" title="<?= GetMessage("Распечатать")?>"><i class="fa fa-print" aria-hidden="true"></i></a>
                    <a data-toggle="tooltip" data-placement="top" href="#" class="js-popup-open" data-popup="#popup-subscribe-phone" title="<?= GetMessage("Отправить SMS")?>"><i class="fa fa-mobile" aria-hidden="true"></i></a>
                </div>
            </div>
        </div>

        <div class="news-one-cnt">
            <div class="padding-news">
                <div class="date">
                    <sub><?= $date_from ?></sub>
                </div>
                <? if (!empty($arResult['PREVIEW_TEXT'])): ?><p><?= $arResult['PREVIEW_TEXT'] ?></p><? endif; ?>
            </div>

            <? if (!empty($arResult['DETAIL_PICTURE']['SRC'])):
                $share_img_src = $arResult['DETAIL_PICTURE']['SRC'];?>
                <div class="news-detail-pic">
                    <div class="img-wrap">
                        <img class="js-popup-open-img" title="<?=$arResult['NAME']?>" src="<?= $arResult['DETAIL_PICTURE']['SRC'] ?>" alt=""/>
                    </div>
                </div>
            <? endif; ?>

            <div class="padding-news">
                <?= $arResult['DETAIL_TEXT'] ?>

                <?if (!empty($arResult['PROPERTIES']['photogallery']['VALUE'])): ?><h2>Фотогалерея</h2><? endif; ?>
            </div>
            <div class="row gallery-slider" id="galElWidth">
                <? if (count($arResult['PROPERTIES']['photogallery']['VALUE']) > 3) { ?>
                    <div id="normal-slider-init" class="controls">
                        <div class="prev" id="galP"><i class="fa fa-arrow-circle-o-left"></i></div>
                        <div class="next" id="galN"><i class="fa fa-arrow-circle-o-right"></i></div>
                    </div>
                <?}?>
                <? if (count($arResult['PROPERTIES']['photogallery']['VALUE']) > 1) { ?>
                    <div id="normal-slider-init-mobile" class="controls controls-mobile">
                        <div class="prev" id="galP"><i class="fa fa-arrow-circle-o-left"></i></div>
                        <div class="next" id="galN"><i class="fa fa-arrow-circle-o-right"></i></div>
                    </div>
                <?}?>
                <ul class="js-normal-slider-init" style="min-width: 500px" data-width="#galElWidth" data-next="#galN" data-prev="#galP">
                    <? $countGalImg = 0;?>
                   
                    <?
                    foreach ($arResult['PROPERTIES']['photogallery']['VALUE'] as $key => $photo):
                        $photoBig = CFile::GetPath($photo);
                        $photo = CFile::ResizeImageGet($photo, array('width'=>320, 'height'=>320),BX_RESIZE_IMAGE_EXACT, true);
                        if  (!$key) {
                            $share_img_src = $photo['src'];
                        }
                        ?>
                        <li>
                            <div class="img-wrap">
                                <img class="js-popup-open-img" title="<?=$arResult['NAME']?>" data-big-img="<?=$photoBig?>" data-group="gallG" src="<?= $photo['src'] ?>" alt=""/>
                            </div>
                        </li>
                    <? endforeach; ?>
                </ul>
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
                                nextSelector: $('#normal-slider-init').find('.next'),
                                prevSelector: $('#normal-slider-init').find('.prev'),
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
                                nextSelector: $('#normal-slider-init-mobile').find('.next'),
                                prevSelector: $('#normal-slider-init-mobile').find('.prev')
                            };
                            return ($(window).width()<768) ? settings2 : settings1;
                        }

                        var mySlider;

                        function tourLandingScript() {
                            mySlider.reloadSlider(settings());
                        }

                        mySlider = $('.js-normal-slider-init').bxSlider(settings());
                        $(window).resize(tourLandingScript);
                    });
                </script>
            </div>
            <? /*endif*/ ?>

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
    <div class="col-md-3 col-xs-12 aside aside-mobile">
        <?$APPLICATION->IncludeComponent(
            "kontora:element.list",
            "news_detail",
            Array(
                "IBLOCK_ID" => 3,
                'FILTER' => array('!ID' => $arResult['ID'], 'PROPERTY_catalog_section' => $arResult['PROPERTIES']['catalog_section']['VALUE']),
                'ELEMENT_COUNT' => 4,
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "0",
                'ORDER' => array('active_from' => 'desc'),
                "PROPS" => "Y",
                "CHECK_PERMISSIONS" => "Y",
            )
        );?>
    </div>
</div>
