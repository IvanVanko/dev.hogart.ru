<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<div class="row vertical-align">
    <div class="col-md-9 col-sm-12 brand-detail__controls">
        <h3 style="margin-top: 10px"><?= $arResult['NAME'] ?></h3>
        <div class="brand-controls controls text-right">
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
    <div class="col-md-3 text-right brand-documentation">
        <div class="hogart-share text-right">
            <a class="hogart-share__link-button" data-toggle="tooltip" data-placement="top" title="Документация" href="<?= SITE_DIR ?>documentation/<?= $arResult['CODE'] ?>/"><!-- <i class="fa fa-file-archive-o" aria-hidden="true"></i> -->Документация</a>
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
    <div class="col-md-3 col-sm-12">
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
        <a class="brand-link" target="_blank" href="<?= $arResult['PROPERTIES']['site']['VALUE'] ?>"><?= $arResult['PROPERTIES']['site']['VALUE'] ?></a>
    </div>
    <div class="col-md-9 col-sm-12 brand-mobile__text">
        <?= $arResult['PREVIEW_TEXT'] ?>
        <? if($arResult['DETAIL_TEXT']): ?>
            <div class="brand__detail-link">
                <a title="Подробнее"  href="#brand-detail">Подробнее >></a>
            </div>
        <? endif; ?>
    </div> 
</div>

<div class="brand-catalog__mobile" style="margin-top: 20px;">
    <div class="brand-catalog__category">
        <div class="brand-catalog__title title h4 text-uppercase">Каталог продукции <span class="brand-name"><?= $arResult['NAME'] ?></span></div>
        <a class="brand-catalog__accordion" data-toggle="collapse" data-parent="#accordion-brand" href="#brand-category" aria-expanded="false" title="Каталог">
            <span class="brand-catalog__control">
                <span class="brand-catalog__plus">+</span >
                <span class="brand-catalog__minus">-</span>
            </span >
            <span class="brand-catalog__text">Каталог</span>
        </a>
        <div id="brand-category" class="brand-catalog__content collapse panel-collapse panel panel-default"> 
            <? if (!empty($arResult['PARENT_SECTIONS']) && !empty($arResult['PRODUCT_SECTION_GROUPS']) && !empty($arResult['PRODUCT_GROUPS'])): ?>
                <ul id="#accordion-sub-brand" class="brand-catalog__list">
                    <? foreach ($arResult['PARENT_SECTIONS'] as $arParentSection): ?>
                        <li class="panel panel-default">
                            <a data-toggle="collapse" data-parent="#accordion-sub-brand" href="#<?=$arParentSection['ID'] ?>" aria-expanded="false" title="Каталог" class="h4 text-uppercase catalog-mobile__name"><?= $arParentSection['NAME'] ?></a>
                            <ul id="<?=$arParentSection['ID'] ?>" class="catalog-mobile__description catalog-mobile__description--brand panel-collapse collapse">
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
            <? else: ?>
                <p class="no-catalog">
                    Для подбора оборудования <span class="brand-name"><?= $arResult['NAME'] ?></span>
                     <a href="<?= SITE_DIR ?>contacts/tsentralnyy-ofis-khogart-v-moskve-sklad-i-servisnaya-sluzhba/">обращайтесь к менеджерам компании</a>
                </p>
            <? endif; ?>
        </div>
    </div>
</div>

<? if($arResult['PREVIEW_TEXT'] || $arResult['DETAIL_TEXT']): ?>
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-12">
            <div class="brand-catalog__description">
                <a class="brand-catalog__accordion" data-toggle="collapse" data-parent="#accordion-brand" href="#brand-description" aria-expanded="true" title="Описание">
                    <span class="brand-catalog__control">
                        <span class="brand-catalog__plus">+</span >
                        <span class="brand-catalog__minus">-</span>
                    </span >
                    <span class="brand-catalog__text">Описание</span>
                </a>
                <div id="brand-description" class="brand-catalog__content collapse panel-collapse in panel panel-default">
                    <div class="col-md-9 col-sm-12 brand-mobile__accordion-text">
                        <?= $arResult['PREVIEW_TEXT'] ?>
                    </div>
                    <? foreach (['about_company' => "О компании", 'about_products' => "О продуктах и решениях"] as $gallery_key => $section_name): ?>
                        <? if (!empty($arResult["PROPERTIES"]["photogallery_" . $gallery_key]['VALUE']) || !empty($arResult["PROPERTIES"]["videogallery_" . $gallery_key]['VALUE'])): ?>
                            <div class="row">
                                <div class="col-md-12 <?= $gallery_key?>">
                                    <h4><?= $section_name ?></h4>
                                    <? if (count($arResult["PROPERTIES"]["photogallery_" . $gallery_key]['VALUE']) + count($arResult["PROPERTIES"]["videogallery_" . $gallery_key]['VALUE']) > 1):?>
                                        <div id="js-service-slider-mobile-<?= $gallery_key ?>" class="controls controls-mobile text-right">
                                            <div class="prev"><i class="fa fa-arrow-circle-o-left"></i></div>
                                            <div class="next"><i class="fa fa-arrow-circle-o-right"></i></div>
                                        </div>
                                    <? endif; ?>
                                    <? if (count($arResult["PROPERTIES"]["photogallery_" . $gallery_key]['VALUE']) + count($arResult["PROPERTIES"]["videogallery_" . $gallery_key]['VALUE']) > 3):?>
                                        <div id="js-service-slider-<?= $gallery_key ?>" class="controls text-right">
                                            <div class="prev"><i class="fa fa-arrow-circle-o-left"></i></div>
                                            <div class="next"><i class="fa fa-arrow-circle-o-right"></i></div>
                                        </div>
                                    <? endif; ?>

                                    <ul class="sert-slider-cnt js-service-slider-<?=$gallery_key?>">
                                        <?foreach ($arResult["PROPERTIES"]["photogallery_" . $gallery_key]["VALUE"] as $key => $picId):
                                            $file = CFile::ResizeImageGet($picId, array('width'=>320, 'height'=>180), BX_RESIZE_IMAGE_EXACT, true);
                                            $fileBig = CFile::ResizeImageGet($picId, array('width'=>800, 'height'=>600), BX_RESIZE_IMAGE_EXACT, true);
                                            ?>
                                            <li>
                                                <div class="img-wrap">
                                                    <img class="js-popup-open-img" src="<?= $file['src']; ?>" title="<?= $arResult["PROPERTIES"]["photogallery_" . $gallery_key]["DESCRIPTION"][$key] ?>" data-group="gallG" data-big-img="<?= $fileBig['src']; ?>" alt=""/>
                                                </div>
                                            </li>
                                        <?endforeach?>

                                        <? foreach ($arResult["PROPERTIES"]["videogallery_" . $gallery_key]["VALUE"] as $key => $picId): ?>
                                            <li>
                                                <div class="img-wrap video">
                                                    <img class="js-popup-open-img" title="<?=$arResult["PROPERTIES"]["videogallery_" . $gallery_key]["DESCRIPTION"][$key] ?>" data-big-video="<?=$arResult["PROPERTIES"]["videogallery_" . $gallery_key]['VALUE'][$key]?>" src="http://img.youtube.com/vi/<?=$arResult["PROPERTIES"]["videogallery_" . $gallery_key]['VALUE'][$key]?>/mqdefault.jpg"  alt=""/>
                                                </div>
                                            </li>
                                        <? endforeach; ?>
                                    </ul>
                                    <div id="slide-counter"></div>
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
                                                    nextSelector: $('#js-service-slider-<?= $gallery_key ?>').find('.next'),
                                                    prevSelector: $('#js-service-slider-<?= $gallery_key ?>').find('.prev'),
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
                                                    nextSelector: $('#js-service-slider-mobile-<?= $gallery_key ?>').find('.next'),
                                                    prevSelector: $('#js-service-slider-mobile-<?= $gallery_key ?>').find('.prev')
                                                };
                                                return ($(window).width()<768) ? settings2 : settings1;
                                            }

                                            var mySlider;

                                            function tourLandingScript() {
                                                mySlider.reloadSlider(settings());
                                            }

                                            mySlider = $('.js-service-slider-<?= $gallery_key ?>').bxSlider(settings());
                                            $(window).resize(tourLandingScript);
                                        });
                                    </script>
                                </div>
                            </div>
                        <? endif; ?>
                    <? endforeach; ?>
                    <div id="brand-detail" class="brand__detail">
                        <?= $arResult['DETAIL_TEXT'] ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<? endif; ?>

<? $this->SetViewTarget('brand-catalog') ?>
    <div class="title h4 text-uppercase">Каталог продукции <span class="brand-name"><?= $arResult['NAME'] ?></span></div>
    <? if (!empty($arResult['PARENT_SECTIONS']) && !empty($arResult['PRODUCT_SECTION_GROUPS']) && !empty($arResult['PRODUCT_GROUPS'])): ?>
        <ul class="brand-aside">
            <? foreach ($arResult['PARENT_SECTIONS'] as $arParentSection): ?>
                <li class="brand-aside__item">
                    <a class="brand-aside__title h4 text-uppercase" href="<?= $arParentSection['SECTION_PAGE_URL'] ?>"><?= $arParentSection['NAME'] ?></a>
                    <ul class="brand-aside__submenu">
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
    <? else: ?>
        <p class="no-catalog">
            Для подбора оборудования <span class="brand-name"><?= $arResult['NAME'] ?></span>
             <a href="<?= SITE_DIR ?>contacts/tsentralnyy-ofis-khogart-v-moskve-sklad-i-servisnaya-sluzhba/">обращайтесь к менеджерам компании</a>
        </p>
    <? endif; ?>
<? $this->EndViewTarget() ?>

<?$APPLICATION->IncludeFile(
    "/local/include/share.php",
    array(
        "TITLE" => $arResult["NAME"],
        "DESCRIPTION" => !empty($arResult["PREVIEW_TEXT"]) ? $arResult["PREVIEW_TEXT"]:$arResult["DETAIL_TEXT"],
        "LINK" => $APPLICATION->GetCurPage(),
        "IMAGE"=> $share_img_src
    )
);?>