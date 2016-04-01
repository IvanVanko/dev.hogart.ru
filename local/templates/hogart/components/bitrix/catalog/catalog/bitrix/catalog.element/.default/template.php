<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

?>

<? if(!empty($arResult["DISPLAY_PROPERTIES"]["sku"]["VALUE"])): ?>
    <div class="art">Артикул: <span><?=$arResult["DISPLAY_PROPERTIES"]["sku"]["VALUE"]?></span></div>
<? endif; ?>
<h1><?=$arResult["NAME"]?></h1>
<div class="product-info">
    <div class="img-wrap <?=(count($arResult["DISPLAY_PROPERTIES"]["photos"]["VALUE"]) > 1) ? '' : 'count1'?>">
        <? if($USER->IsAuthorized() && !empty($arResult["PRICES"]["BASE"]["DISCOUNT_DIFF_PERCENT"])): ?>
            <div class="sale">
                -<?=$arResult["PRICES"]["BASE"]["DISCOUNT_DIFF_PERCENT"]?>%
            </div>
        <? endif; ?>
        <div class="bxslider">
            <ul class="bx-wrap" id="js-scale-01">
                <? if(!empty($arResult['PREVIEW_PICTURE']['SRC'])) { ?>
                    <li>
                        <div class="img-wrap">
                            <img data-big-img="<?=$arResult['PREVIEW_PICTURE']['SRC']?>" title="<?=$arResult['NAME']?>"
                                 src="<?
                                 $pic = CFile::ResizeImageGet(
                                     $arResult['PREVIEW_PICTURE']['ID'],
                                     array("width" => 500, "height" => 228),
                                     BX_RESIZE_IMAGE_PROPORTIONAL,
                                     true);

                                 echo $pic['src']; ?>" data-group="producPop"
                                 class="js-popup-open-img" alt=""/>
                        </div>
                    </li>
                <?
                }
                elseif(!empty($arResult['DETAIL_PICTURE']['SRC'])) { ?>
                    <li>
                        <div class="img-wrap">
                            <img data-big-img="<?=$arResult['DETAIL_PICTURE']['SRC']?>" title="<?=$arResult['NAME']?>"
                                 src="<?
                                 $pic = CFile::ResizeImageGet(
                                     $arResult['DETAIL_PICTURE']['ID'],
                                     array("width" => 500, "height" => 228),
                                     BX_RESIZE_IMAGE_PROPORTIONAL,
                                     true);

                                 echo $pic['src']; ?>" data-group="producPop"
                                 class="js-popup-open-img" alt=""/>
                        </div>
                    </li>
                <?
                }
                else { ?>
                    <li>
                        <div class="img-wrap">
                            <img src="/images/no-img-big.jpg" data-group="producPop" />
                        </div>
                    </li>
                <? } ?>
            </ul>
        </div>
        <!--            --><? //else: ?>
        <!--                <img src="--><? //=$arResult['PREVIEW_PICTURE']['SRC'] ?><!--" alt=""/>-->
        <!--            --><? //endif; ?>
    </div>
    <div class="info-wrap">
        <form action="#">
            <ul class="list-href">
                <li class="item"><a href="#" class="icon-info js-accordion" data-accordion="#hide1">Информация</a></li>
                <li id="hide1" style="display: list-item;">
                    <div class="product-info-block">
                        <div class="row">
                            <div class="col2">
                                <div class="price currency-<?=strtolower($arResult["PRICES"]["BASE"]["CURRENCY"])?>">
                                    <? if($USER->IsAuthorized() && !empty($arResult["PRICES"]["BASE"]["DISCOUNT_DIFF_PERCENT"])): ?>
                                        <?=HogartHelpers::wPrice($arResult["PRICES"]["BASE"]["PRINT_DISCOUNT_VALUE"]);?>
                                    <? else: ?>
                                        <?=HogartHelpers::wPrice($arResult["PRICES"]["BASE"]["PRINT_VALUE"]);?>
                                    <? endif; ?>
                                </div>
                            </div>
                            <? if($USER->IsAuthorized()): ?>
                                <div class="col2 text-right">
                                    <a class="empty-btn black grid-hide" href="#"><i class="icon-cart"></i> Купить</a>
                                </div><? endif; ?>
                            <? if(!$USER->IsAuthorized()): ?>
                                <div class="col2 ">
                                    <div class="info-block rozn">
                                        <div class="discount"></div>
                                        <p>Рекомендуемая<br/>розничная цена</p>
                                    </div>
                                </div>
                            <? endif; ?>
                        </div>
                        <!--Только для авторизованных-->
                        <? if($USER->IsAuthorized() && !empty($arResult["PRICES"]["BASE"]["DISCOUNT_DIFF_PERCENT"])): ?>
                            <div class="info-block">
                                <div class="discount">-<?=$arResult["PRICES"]["BASE"]["DISCOUNT_DIFF_PERCENT"]?>%</div>
                                <div
                                    class="old currency-<?=strtolower($arResult["PRICES"]["BASE"]["CURRENCY"])?>"><?=HogartHelpers::wPrice($arResult["PRICES"]["BASE"]["PRINT_VALUE"]);?></div>
                                <p>Цена указана с учетом скидки клиента</p>
                            </div>
                        <? else: ?>
                            <!--				                <div class="info-block rozn">-->
                            <!--				                    <div class="discount"></div>-->
                            <!--				                    <p>Рекомендуемая<br />розничная цена</p>-->
                            <!--				                </div>-->
                            <small style="padding-right: 150px; display: inline-block">
                                Для покупки данного товара необходимо зарегистрироваться или войти
                            </small>
                        <? endif; ?>
                        <!---->
                        <hr>
                        <div class="row">
                            <? if($arResult["CATALOG_QUANTITY"] > 0): ?>
                                <div class="col2">
                                    <div class="icon-carTon grid-hide">
                                        В наличии<? if($USER->IsAuthorized()): ?>
                                            <span><?=$arResult["CATALOG_QUANTITY"];?> шт.</span><? endif; ?>
                                    </div>
                                </div>
                            <? else: ?>
                                <div class="col2">
                                    <div class="icon-carTon grid-hide">
                                        Под заказ
                                        <? if(!empty($arResult["PROPERTIES"]["delivery_period"]["VALUE"])): ?>
                                            <br>
                                            <span>Срок поставки <?=$arResult["PROPERTIES"]["delivery_period"]["VALUE"]?> <?=number($arResult["PROPERTIES"]["delivery_period"]["VALUE"], array('день',
                                                                                                                                                                                              'дня',
                                                                                                                                                                                              'дней'))?></span>
                                        <? endif; ?>
                                    </div>
                                </div>
                                <? if(!empty($arResult["PROPERTIES"]["supply"]["VALUE"])): ?>
                                    <div class="col2">
                                        <div class="icon-sheTon grid-hide">
                                            ожидаемое поступление <br>
                                            <?=FormatDate('d F',
                                                MakeTimeStamp($arResult["PROPERTIES"]["supply"]["VALUE"]));?>
                                        </div>
                                    </div>
                                <? endif; ?>
                            <? endif; ?>
                        </div>
                    </div>
                </li>

                <? if(count($arResult["DOCS"]) > 0): ?>
                    <li class="<?=(count($arResult["DOCS"]) > 0) ? '' : 'off '?>item">
                        <a href="#" class="icon-doc js-accordion" data-accordion="#hide2">Документация</a>
                    </li>
                    <li id="hide2">
                        <div class="features doc-features">

                            <div class="doc-cnt mCustomScrollbar" data-mcs-theme="dark">
                                <? foreach($arResult["DOCS"] as $arDocument): ?>
                                    <a class="file-pdf" target="_blank"
                                       href="/download.php?id=<?=$arDocument["PROPERTIES"]["file"]["VALUE"]?>&name=<?=$arDocument["NAME"]?>">
                                        <?=$arDocument["NAME"]?>
                                        <span><?=$arDocument["FILE"]["EXTENTION"]?>
                                            , <?=$arDocument["FILE"]["FILE_SIZE"]?> mb</span>
                                    </a>
                                <? endforeach; ?>
                            </div>

                        </div>
                    </li>
                <? endif; ?>

                <? if(!empty($arResult["PROPERTIES"]['video_youtube']['~VALUE'])): ?>

                    <li class="<?=(!empty($arResult["PROPERTIES"]['video_youtube']['~VALUE'])) ? '' : 'off ';?>item">
                        <a href="#" class="icon-video js-accordion" data-accordion="#hide3">Видеоматериалы</a></li>
                    <li id="hide3" class="video-show">
                        <div class="video-block">

                            <div class="video-item product-small">
                                <!--                                <img alt="" src="/images/company_video_03.jpg">-->
                                <iframe width="370" height="208"
                                        src="https://www.youtube.com/embed/<?=$arResult["PROPERTIES"]['video_youtube']['VALUE']?>?rel=0"
                                        frameborder="0" allowfullscreen></iframe>
                            </div>

                        </div>
                    </li>

                <? endif; ?>
                <!-- li class="off item"><a class="icon-video" href="#">Видеоматериалы</a></li -->
            </ul>
        </form>
    </div>
    <div class="clearfix"></div>
    <div class="features">
        <div class="head">
            <h2 class="feat">Характеристики</h2>
        </div>
        <div class="features-cnt">
            <? if($arResult["CUSTOM"]["BRAND_NAME"]) { ?>
                <dl>
                    <dt><?=$arResult["DISPLAY_PROPERTIES"]["brand"]["NAME"]?></dt>
                    <dd><?=$arResult["CUSTOM"]["BRAND_NAME"]?></dd>
                </dl>
            <? } ?>
            <? if(!empty($arResult['DISPLAY_PROPERTIES']['collection']['LINK_ELEMENT_VALUE'])) {
                $collection_element = current($arResult['DISPLAY_PROPERTIES']['collection']['LINK_ELEMENT_VALUE']) ?>
                <dl>
                    <dt><?=$arResult['DISPLAY_PROPERTIES']['collection']['NAME']?></dt>
                    <dd><?=$collection_element['NAME']?></dd>
                </dl>
            <? } ?>
            <? $arShownProperties = array(); ?>
            <? $arHiddenProperties = array(); ?>
            <? foreach($arResult["PROPERTIES"] as $arProperty) {
                if(!empty($arProperty["VALUE"]) && $arProperty['CODE'] != 'brand' && $arProperty['CODE'] != 'collection') {
                    if($arProperty["DISPLAY_EXPANDED"] == 'Y') {
                        $arShownProperties[] = $arProperty;
                    }
                    else {
                        $arHiddenProperties[] = $arProperty;
                    }
                }
            } ?>
            <? if(!empty($arShownProperties)) { ?>
                <div>
                    <? foreach($arShownProperties as $propertyName => $arProperty): ?>
                        <dl>
                            <dt><?=$arProperty["NAME"]?> <?=($USER->IsAdmin()) ? $arProperty['CUSTOM_SECTION_SORT']." ".$arProperty['DISPLAY_EXPANDED'] : ""?></dt>
                            <dd><?=$arProperty["VALUE"]?></dd>
                        </dl>
                    <? endforeach; ?>
                </div>
            <? } ?>
            <? if(!empty($arHiddenProperties)) { ?>
                <div class="collapse" id="show-element-props">
                    <? foreach($arHiddenProperties as $propertyName => $arProperty): ?>
                        <dl>
                            <dt><?=$arProperty["NAME"]?> <?=($USER->IsAdmin()) ? $arProperty['CUSTOM_SECTION_SORT']." ".$arProperty['DISPLAY_EXPANDED'] : ""?></dt>
                            <dd><?=$arProperty["VALUE"]?></dd>
                        </dl>
                    <? endforeach; ?>
                </div>
                <dl data-active-label="Скрыть" data-hidden-label="Все характеристики"
                    data-collapse="#show-element-props" class="features-show-all">
                    <dt><span>Все характеристики</span></dt>
                    <dd></dd>
                </dl>
            <? } ?>
        </div>
    </div>

</div>
<? $APPLICATION->IncludeFile(
    "/local/include/share.php",
    array(
        "TITLE" => $arResult["NAME"],
        "DESCRIPTION" => !empty($arResult["PREVIEW_TEXT"]) ? $arResult["PREVIEW_TEXT"] : $arResult["DETAIL_TEXT"],
        "LINK" => $APPLICATION->GetCurPage(),
        "IMAGE" => $share_img_src
    )
); ?>
</div>
<div class="products-similar-tabs inner">
    <script type="text/javascript">
        $(document).ready(function () {
            $('.tabs-similar li').first().children('a').click();
        });
    </script>
    <ul class="tabs-similar">
        <? if(isset($arResult["buy_with_this"])): ?>
            <li><a href="#tab-1" class="active">С этим товаром покупают</a></li>
        <? endif; ?>
        <? if(isset($arResult["related"])): ?>
            <li><a href="#tab-2">Сопутствующие товары</a></li>
        <? endif; ?>

        <? if(isset($arResult["alternative"])): ?>
            <li><a href="#tab-3">Альтернативные товары</a></li>
        <? endif; ?>

        <? if(isset($arResult["this_collection"])): ?>
            <li><a href="#tab-4">Еще из этой коллекции</a></li>
        <? endif; ?>
    </ul>
    <div class="items-similar">
        <? if(isset($arResult["buy_with_this"])): ?>
            <div id="tab-1" class="item-similar active">
                <div id="con-1" class="controls">
                    <div class="prev"></div>
                    <div class="next"></div>
                </div>
                <ul data-control="#con-1" class="js-slider-similar">
                    <? foreach($arResult["buy_with_this"] as $key => $arProduct): ?>
                        <li>
		        	<span class="preview-img">
                        <? if(!empty($arProduct["PREVIEW_PICTURE"])) {
                            //$file = CFile::GetPath($arProduct["PREVIEW_PICTURE"]);
                            $file = CFile::ResizeImageGet($arProduct["PREVIEW_PICTURE"],
                                array('width' => 406, 'height' => 142), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
                            $file = $file['src'];
                        }
                        elseif(!empty($arProduct["PROPERTY_PHOTOS_VALUE"])) {
                            //$file = CFile::GetPath($arProduct["PROPERTY_PHOTOS_VALUE"]);
                            $file = CFile::ResizeImageGet($arProduct["PROPERTY_PHOTOS_VALUE"],
                                array('width' => 406, 'height' => 142), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
                            $file = $file['src'];
                        }
                        else {
                            $file = '/images/project_no_img.jpg';
                        } ?>
                        <a href="<?=$arProduct["DETAIL_PAGE_URL"]?>" style="background-image: url(<?=$file?>)">
		        	</span>
                            <a href="<?=$arProduct["DETAIL_PAGE_URL"]?>"><h3><?=$arProduct["NAME"]?></h3></a>
                            <? if(!empty($arProduct["PROPERTY_SKU_VALUE"])): ?>
                                <span class="art">Артикул: <span><?=$arProduct["PROPERTY_SKU_VALUE"]?></span></span>
                            <? endif; ?>
                            <div class="param">
                                <div>
                                    <dl>
                                        <dt>Бренд</dt>
                                        <dd class="pr"><?=$arProduct['BRAND_NAME']?></dd>
                                    </dl>
                                    <dl>
                                        <dt>Коллекция</dt>
                                        <dd class="pr"><?=$arProduct['COLLECTION_NAME']?></dd>
                                    </dl>
                                </div>
                                <?=HogartHelpers::getAdjacentProductPropertyHtml($arProduct['ID'], $arProduct["SHOW_PROPS"], $arProduct["HIDDEN_PROPS"], array('brand',
                                                                                                                                                               'photos',
                                                                                                                                                               'sku',
                                                                                                                                                               'collection'));?>
                            </div>
                            <div class="price currency-<?=strtolower($arProduct['CATALOG_CURRENCY_1'])?>">
                                <?=HogartHelpers::wPrice($arProduct['PRICE'])?>
                            </div>
                        </li>
                    <? endforeach; ?>
                </ul>

            </div>
        <? endif; ?>

        <? if(isset($arResult["related"])): ?>
            <div id="tab-2" class="item-similar">
                <div id="con-2" class="controls">
                    <div class="prev"></div>
                    <div class="next"></div>
                </div>
                <ul data-control="#con-2" class="js-slider-similar ">
                    <? foreach($arResult["related"] as $arProduct): ?>
                        <li>
		        	<span class="preview-img">
                        <? if(!empty($arProduct["PREVIEW_PICTURE"])) {
                            //$file = CFile::GetPath($arProduct["PREVIEW_PICTURE"]);
                            $file = CFile::ResizeImageGet($arProduct["PREVIEW_PICTURE"],
                                array('width' => 406, 'height' => 142), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
                            $file = $file['src'];
                        }
                        elseif(!empty($arProduct["PROPERTY_PHOTOS_VALUE"])) {
                            //$file = CFile::GetPath($arProduct["PROPERTY_PHOTOS_VALUE"]);
                            $file = CFile::ResizeImageGet($arProduct["PROPERTY_PHOTOS_VALUE"],
                                array('width' => 406, 'height' => 142), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
                            $file = $file['src'];
                        }
                        else {
                            $file = '/images/project_no_img.jpg';
                        } ?>
                        <a href="<?=$arProduct["DETAIL_PAGE_URL"]?>" style="background-image: url(<?=$file?>)">
		        	</span>
                            <a href="<?=$arProduct["DETAIL_PAGE_URL"]?>"><h3><?=$arProduct["NAME"]?></h3></a>
                            <? if(!empty($arProduct["PROPERTY_SKU_VALUE"])): ?>
                                <span class="art">Артикул: <span><?=$arProduct["PROPERTY_SKU_VALUE"]?></span></span>
                            <? endif; ?>
                            <div class="param">
                                <div>
                                    <dl>
                                        <dt>Бренд</dt>
                                        <dd class="pr"><?=$arProduct['BRAND_NAME']?></dd>
                                    </dl>
                                    <dl>
                                        <dt>Коллекция</dt>
                                        <dd class="pr"><?=$arProduct['COLLECTION_NAME']?></dd>
                                    </dl>
                                </div>
                                <?=HogartHelpers::getAdjacentProductPropertyHtml($arProduct['ID'], $arProduct["SHOW_PROPS"], $arProduct["HIDDEN_PROPS"], array('brand',
                                                                                                                                                               'photos',
                                                                                                                                                               'sku',
                                                                                                                                                               'collection'));?>
                            </div>
                            <div class="price currency-<?=strtolower($arProduct['CATALOG_CURRENCY_1'])?>">
                                <?=HogartHelpers::wPrice($arProduct['PRICE'])?>
                            </div>
                        </li>
                    <? endforeach; ?>
                </ul>

            </div>
        <? endif; ?>

        <? if(isset($arResult["alternative"])): ?>
            <div id="tab-3" class="item-similar">
                <div id="con-3" class="controls">
                    <div class="prev"></div>
                    <div class="next"></div>
                </div>
                <ul data-control="#con-3" class="js-slider-similar">
                    <? foreach($arResult["alternative"] as $arProduct): ?>
                        <li>
		        	<span class="preview-img">
                        <? if(!empty($arProduct["PREVIEW_PICTURE"])) {
                            //$file = CFile::GetPath($arProduct["PREVIEW_PICTURE"]);
                            $file = CFile::ResizeImageGet($arProduct["PREVIEW_PICTURE"],
                                array('width' => 406, 'height' => 142), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
                            $file = $file['src'];
                        }
                        elseif(!empty($arProduct["PROPERTY_PHOTOS_VALUE"])) {
                            //$file = CFile::GetPath($arProduct["PROPERTY_PHOTOS_VALUE"]);
                            $file = CFile::ResizeImageGet($arProduct["PROPERTY_PHOTOS_VALUE"],
                                array('width' => 406, 'height' => 142), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
                            $file = $file['src'];
                        }
                        else {
                            $file = '/images/project_no_img.jpg';
                        } ?>
                        <a href="<?=$arProduct["DETAIL_PAGE_URL"]?>" style="background-image: url(<?=$file?>)">
		        	</span>
                            <a href="<?=$arProduct["DETAIL_PAGE_URL"]?>"><h3><?=$arProduct["NAME"]?></h3></a>
                            <? if(!empty($arProduct["PROPERTY_SKU_VALUE"])): ?>
                                <span class="art">Артикул: <span><?=$arProduct["PROPERTY_SKU_VALUE"]?></span></span>
                            <? endif; ?>
                            <div class="param">
                                <div>
                                    <dl>
                                        <dt>Бренд</dt>
                                        <dd class="pr"><?=$arProduct['BRAND_NAME']?></dd>
                                    </dl>
                                    <dl>
                                        <dt>Коллекция</dt>
                                        <dd class="pr"><?=$arProduct['COLLECTION_NAME']?></dd>
                                    </dl>
                                </div>
                                <?=HogartHelpers::getAdjacentProductPropertyHtml($arProduct['ID'], $arProduct["SHOW_PROPS"], $arProduct["HIDDEN_PROPS"], array('brand',
                                                                                                                                                               'photos',
                                                                                                                                                               'sku',
                                                                                                                                                               'collection'));?>
                            </div>
                            <div class="price currency-<?=strtolower($arProduct['CATALOG_CURRENCY_1'])?>">
                                <?=HogartHelpers::wPrice($arProduct['PRICE'])?>
                            </div>
                        </li>
                    <? endforeach; ?>
                </ul>

            </div>
        <? endif; ?>

        <? if(isset($arResult["this_collection"])): ?>
            <div id="tab-4" class="item-similar">
                <? if(count($arResult["this_collection"]) > 3): ?>
                    <div id="con-4" class="controls">
                        <div class="prev"></div>
                        <div class="next"></div>
                    </div>
                <? endif; ?>
                <ul data-control="#con-4" class="js-slider-similar">
                    <? foreach($arResult["this_collection"] as $arProduct): ?>
                        <li>
		        	<span class="preview-img">
                        <? if(!empty($arProduct["PREVIEW_PICTURE"])) {
                            //$file = CFile::GetPath($arProduct["PREVIEW_PICTURE"]);
                            $file = CFile::ResizeImageGet($arProduct["PREVIEW_PICTURE"],
                                array('width' => 406, 'height' => 142), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
                            $file = $file['src'];
                        }
                        elseif(!empty($arProduct["PROPERTY_PHOTOS_VALUE"])) {
                            //$file = CFile::GetPath($arProduct["PROPERTY_PHOTOS_VALUE"]);
                            $file = CFile::ResizeImageGet($arProduct["PROPERTY_PHOTOS_VALUE"],
                                array('width' => 406, 'height' => 142), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
                            $file = $file['src'];
                        }
                        else {
                            $file = '/images/project_no_img.jpg';
                        } ?>
                        <a href="<?=$arProduct["DETAIL_PAGE_URL"]?>" style="background-image: url(<?=$file?>)"></a>
		        	</span>
                            <a href="<?=$arProduct["DETAIL_PAGE_URL"]?>"><h3><?=$arProduct["NAME"]?></h3></a>
                            <? if(!empty($arProduct["PROPERTY_SKU_VALUE"])): ?>
                                <span class="art">Артикул: <span><?=$arProduct["PROPERTY_SKU_VALUE"]?></span></span>
                            <? endif; ?>
                            <div class="param">
                                <div>
                                    <dl>
                                        <dt>Бренд</dt>
                                        <dd class="pr"><?=$arProduct['BRAND_NAME']?></dd>
                                    </dl>
                                    <dl>
                                        <dt>Коллекция</dt>
                                        <dd class="pr"><?=$arProduct['COLLECTION_NAME']?></dd>
                                    </dl>
                                </div>
                                <?=HogartHelpers::getAdjacentProductPropertyHtml($arProduct['ID'], $arProduct["SHOW_PROPS"], $arProduct["HIDDEN_PROPS"], array('brand',
                                                                                                                                                               'photos',
                                                                                                                                                               'sku',
                                                                                                                                                               'collection'));?>
                            </div>
                            <div class="price currency-<?=strtolower($arProduct['CATALOG_CURRENCY_1'])?>">
                                <?=HogartHelpers::wPrice($arProduct['PRICE'])?>
                            </div>
                        </li>
                    <? endforeach; ?>
                </ul>
            </div>
        <? endif; ?>
    </div>
</div>

<aside class="sidebar js-fh js-fixed-block js-paralax-height product" data-fixed="top">
    <div class="inner js-paralax-item padding">
        <article>
            <?=$arResult["DETAIL_TEXT"]?>
        </article>
    </div>
</aside>