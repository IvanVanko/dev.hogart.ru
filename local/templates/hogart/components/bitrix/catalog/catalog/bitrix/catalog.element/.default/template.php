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
$collectionComponentId = CAjax::GetComponentID("bitrix:catalog.element", "", "collection");
?>
<? $this->SetViewTarget("related-collection-items"); ?>
<? if(!empty($arResult["this_collection"]["PREV_LINK"]) || !empty($arResult["this_collection"]["NEXT_LINK"])): ?>
    <div id="con-4" class="controls text-right">
        <? if (!empty($arResult["this_collection"]["PREV_LINK"])): ?>
            <div class="prev">
                <a href="<?= $arResult["this_collection"]["PREV_LINK"] ?>" onclick="BX.ajax.insertToNode('<?= $arResult["this_collection"]["PREV_LINK"] ?>', 'com_<?= $collectionComponentId ?>'); return false;">
                    <i class="fa fa-arrow-circle-o-left"></i>
                </a>
            </div>
        <? endif; ?>
        <? if (!empty($arResult["this_collection"]["NEXT_LINK"])): ?>
            <div class="next">
                <a href="<?= $arResult["this_collection"]["NEXT_LINK"] ?>" onclick="BX.ajax.insertToNode('<?= $arResult["this_collection"]["NEXT_LINK"] ?>', 'com_<?= $collectionComponentId ?>'); return false;">
                    <i class="fa fa-arrow-circle-o-right"></i>
                </a>
            </div>
        <? endif; ?>
    </div>
<? endif; ?>

<ul data-control="#con-4" class="row ">
    <? foreach ($arResult["this_collection"]["ITEMS"] as $arProduct): ?>
        <li class="col-lg-3 col-md-4 col-sm-6 this-collection-item">
            <div>
                <span class="perechen-img">
                    <a href="<?= $arProduct["DETAIL_PAGE_URL"] ?>">
                        <?
                        $pic = "/images/project_no_img.jpg";
                        if (!empty($arProduct["PREVIEW_PICTURE"]['SRC'])) {
                            $file = CFile::ResizeImageGet(
                                $arProduct["PREVIEW_PICTURE"]['ID'], array("width" => 400, "height" => 160), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                            if (file_exists(realpath($_SERVER["DOCUMENT_ROOT"] . $file['src'])))
                                $pic = $file['src'];
                        }
                        elseif (!empty($arProduct["DETAIL_PICTURE"]['SRC'])) {

                            $file = CFile::ResizeImageGet(
                                $arProduct["DETAIL_PICTURE"]['ID'], array("width" => 400, "height" => 160), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                            if (file_exists(realpath($_SERVER["DOCUMENT_ROOT"] . $file['src'])))
                                $pic = $file['src'];
                        }
                        ?>
                        <img src="<?=$pic?>" alt=""/>
                    </a>
                </span>

                <div class="prod-box">
                    <? if (!empty($arProduct["PROPERTY_SKU_VALUE"])): ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="art">Артикул: <span><?= $arProduct["PROPERTY_SKU_VALUE"] ?></span></div>
                            </div>
                        </div>
                    <? endif; ?>
                    <div class="row">
                        <div class="col-md-12">
                            <a href="<?= $arProduct["DETAIL_PAGE_URL"] ?>"><h3><?= $arProduct["NAME"] ?></h3></a>
                        </div>
                    </div>
                </div>
                <div class="param-cnt">
                    <ul class="param">
                        <li class="note">
                            <div class="dotted">
                                <span class="text-left">Бренд</span>
                                                    <span class="text-right">
                                                        <span class="pr"><?= $arProduct['BRAND_NAME'] ?></span>
                                                    </span>
                            </div>
                        </li>
                        <? if(!empty($arProduct['COLLECTION_NAME'])): ?>
                            <li class="note">
                                <div class="dotted">
                                    <span class="text-left">Коллекция</span>
                                    <span class="text-right"><?= $arProduct['COLLECTION_NAME'] ?></span>
                                </div>
                            </li>
                        <? endif; ?>
                    </ul>
                </div>
                <div class="price-cnt <? if ($USER->IsAuthorized()): ?> auth-block<? endif; ?>">
                    <div class="row vertical-align">
                        <div class="col-md-6">
                            <div class="price currency-<?= strtolower($arProduct["PRICES"]["BASE"]["CURRENCY"]) ?> text-nowrap">
                                <? if ($USER->IsAuthorized() && !empty($arProduct["PRICES"]["BASE"]["DISCOUNT_DIFF_PERCENT"])): ?>
                                    <?= HogartHelpers::woPrice($arProduct["PRICES"]["BASE"]["PRINT_DISCOUNT_VALUE"]); ?>
                                <? else: ?>
                                    <?= HogartHelpers::woPrice($arProduct["PRICES"]["BASE"]["PRINT_VALUE"]); ?>
                                <? endif; ?>
                                <i class="fa fa-<?=strtolower($arProduct["PRICES"]["BASE"]["CURRENCY"])?>" aria-hidden="true"></i>
                            </div>
                            <!--Только для авторизованных-->
                            <? if ($USER->IsAuthorized() && !empty($arProduct["PRICES"]["BASE"]["DISCOUNT_DIFF_PERCENT"])): ?>
                                <div class="grid-hide discount">
                                    <?= $arProduct["PRICES"]["BASE"]["DISCOUNT_DIFF_PERCENT"] ?>%
                                </div>
                            <? endif; ?>
                            <!---->
                        </div>
                        <div class="col-md-6 text-right text-nowrap">
                            <? if ($arProduct["CATALOG_QUANTITY"] > 0): ?>
                                <div class="quantity quantity-success line <? if ($USER->IsAuthorized()): ?> line2<? endif; ?>">В
                                    наличии<? if ($USER->IsAuthorized()): ?> <span><?= $arProduct["CATALOG_QUANTITY"]; ?>
                                        <?=$arProduct['CATALOG_MEASURE_NAME']?>.</span><? endif; ?></div>
                            <? else: ?>
                                <div class="quantity quantity-fail text-nowrap">
                                    <i class="fa fa-truck" aria-hidden="true"></i> Под заказ
                                </div>
                            <? endif; ?>
                        </div>
                    </div>
                    <!--Только для авторизованных-->
                    <? if ($USER->IsAuthorized() && !empty($arProduct["PRICES"]["BASE"]["DISCOUNT_DIFF_PERCENT"])): ?>
                        <div class="info-block">
                            <div class="old currency">
                                <?= HogartHelpers::woPrice($arProduct["PRICES"]["BASE"]["PRINT_VALUE"]); ?>
                                <i class="fa fa-<?=strtolower($arProduct["PRICES"]["BASE"]["CURRENCY"])?>" aria-hidden="true"></i>
                            </div>
                        </div>
                    <? endif; ?>
                    <? if(!empty($arProduct["PRICES"]["BASE"]["PRINT_VALUE"])): ?>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <?
                                $class_pop = '';
                                $attr_pop = '';
                                ?>
                                <?
                                if (!$USER->IsAuthorized()) {
                                    $class_pop = 'js-popup-open';
                                    $attr_pop = 'data-popup="#popup-msg-product"';
                                }
                                ?>
                                <a id="<?= $arProduct['BUY_URL'] ?>"
                                   class="buy-btn btn btn-primary <?= $class_pop ?>" <?= $attr_pop ?>
                                   href="javascript:void(0)" rel="nofollow">
                                    <i class="fa fa-shopping-cart" aria-hidden="true"></i> Купить
                                </a>
                            </div>
                        </div>
                    <? endif; ?>
                    <!---->
                </div>
            </div>
        </li>
    <? endforeach; ?>
</ul>
<? $this->EndViewTarget(); ?>

<div class="row">
    <div class="col-md-9 main-info">
        <? if(!empty($arResult["DISPLAY_PROPERTIES"]["sku"]["VALUE"])): ?>
            <div class="art">Артикул: <span><?=$arResult["DISPLAY_PROPERTIES"]["sku"]["VALUE"]?></span></div>
        <? endif; ?>
        <h1><?=$arResult["NAME"]?></h1>

        <div class="product-info">
            <div class="row">
                <div class="col-md-6">
                    <div class="img-wrap <?=(count($arResult["DISPLAY_PROPERTIES"]["photos"]["VALUE"]) > 1) ? '' : 'count1'?>">
                        <? if($USER->IsAuthorized() && !empty($arResult["PRICES"]["BASE"]["DISCOUNT_DIFF_PERCENT"])): ?>
                            <!--div class="sale">
                                -<?=$arResult["PRICES"]["BASE"]["DISCOUNT_DIFF_PERCENT"]?>%
                            </div-->
                        <? endif; ?>
                        <div class="bxslider">
                            <ul class="bx-wrap" id="js-scale-01">
                                <? $image_src = "/images/no-img-big.jpg"; ?>
                                <? if(!empty($arResult['PREVIEW_PICTURE']['SRC']) && file_exists($_SERVER["DOCUMENT_ROOT"] . $arResult['PREVIEW_PICTURE']['SRC'])): ?>
                                    <?
                                    $image_big_src = $arResult['PREVIEW_PICTURE'];
                                    ?>
                                <? elseif(!empty($arResult['DETAIL_PICTURE']['SRC']) && file_exists($_SERVER["DOCUMENT_ROOT"] . $arResult['DETAIL_PICTURE']['SRC'])): ?>
                                    <?
                                    $image_big_src = $arResult['DETAIL_PICTURE'];
                                    ?>
                                <? endif; ?>

                                <li>
                                    <div class="img-wrap">
                                        <? if (!empty($image_big_src)): ?>
                                            <?
                                            $pic = CFile::ResizeImageGet(
                                                $image_big_src['ID'],
                                                array("width" => 500, "height" => 228),
                                                BX_RESIZE_IMAGE_PROPORTIONAL,
                                                true);
                                            $image_src = $pic["src"];
                                            ?>
                                        <? endif; ?>
                                        <img <?= (!empty($image_big_src) ? 'data-big-img="' . $image_big_src['SRC'] . '"' : '') ?> title="<?=$arResult['NAME']?>"
                                                                                                                                   src="<?= $image_src ?>" data-group="producPop" class="js-popup-open-img" />
                                    </div>
                                </li>

                                <? foreach ($arResult["DISPLAY_PROPERTIES"]["photos"]["VALUE"] as $photo): ?>
                                    <li>
                                        <div class="img-wrap">
                                            <img data-big-img="<?= CFile::GetPath($photo) ?>"
                                                 src="<?
                                                 $pic = CFile::ResizeImageGet(
                                                     $photo,
                                                     array("width" => 500, "height" => 228),
                                                     BX_RESIZE_IMAGE_PROPORTIONAL,
                                                     true);

                                                 echo $pic['src']; ?>" data-group="producPop"
                                                 class="js-popup-open-img" alt=""/>
                                        </div>
                                    </li>
                                <? endforeach; ?>
                            </ul>
                            <? if (count($arResult["DISPLAY_PROPERTIES"]["photos"]["VALUE"])): ?>
                                <div class="tumb-img">
                                    <?
                                    $photo_small = CFile::ResizeImageGet(
                                        $arResult['PREVIEW_PICTURE']['ID'] ? : $arResult['DETAIL_PICTURE']['ID'],
                                        array("width" => 110, "height" => 52),
                                        BX_RESIZE_IMAGE_EXACT,
                                        true
                                    );
                                    ?>
                                    <a data-slide-index="0" href=""><img src="<?= $photo_small['src'] ?>" alt=""/></a>

                                    <? foreach ($arResult["DISPLAY_PROPERTIES"]["photos"]["VALUE"] as $key => $photo): ?>
                                        <?
                                        $photo_small = CFile::ResizeImageGet(
                                            $photo,
                                            array("width" => 110, "height" => 52),
                                            BX_RESIZE_IMAGE_EXACT,
                                            true
                                        );
                                        ?>
                                        <a data-slide-index="<?= ($key + 1) ?>" href="<?=$arResult["DISPLAY_PROPERTIES"]["photos"]["FILE_VALUE"][$key]['SRC']?>"><img src="<?= $photo_small['src'] ?>" alt=""/></a>
                                    <? endforeach; ?>
                                </div>
                                <?if (count($arResult["DISPLAY_PROPERTIES"]["photos"]["VALUE"]) > 1): ?>
                                    <div class="controls">
                                        <div class="prev"></div>
                                        <div class="next"></div>
                                    </div>
                                <? endif; ?>
                            <? endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-wrap">
                        <div class="row">
                            <div class="col-md-6">
                                
                                <? if (!empty($arResult["PRICES"]["BASE"])): ?>
                                <div class="price text-nowrap">
                                    <? if($USER->IsAuthorized() && !empty($arResult["PRICES"]["BASE"]["DISCOUNT_DIFF_PERCENT"])): ?>
                                        <?=HogartHelpers::woPrice($arResult["PRICES"]["BASE"]["PRINT_DISCOUNT_VALUE"]);?>
                                    <? else: ?>
                                        <?=HogartHelpers::woPrice($arResult["PRICES"]["BASE"]["PRINT_VALUE"]);?>
                                    <? endif; ?>
                                    <i class="fa fa-<?=strtolower($arResult["PRICES"]["BASE"]["CURRENCY"])?>" aria-hidden="true"></i>
                                    <? if($USER->IsAuthorized() && !empty($arResult["PRICES"]["BASE"]["DISCOUNT_DIFF_PERCENT"])): ?>
                                        <sup>*</sup>
                                    <? endif; ?>
                                </div>
                                <? endif; ?>
                                
                                <!--Только для авторизованных-->
                                <? if($USER->IsAuthorized() && !empty($arResult["PRICES"]["BASE"]["DISCOUNT_DIFF_PERCENT"])): ?>
                                    <div class="info-block">
                                        <!--div class="discount">
                                            -<?=$arResult["PRICES"]["BASE"]["DISCOUNT_DIFF_PERCENT"]?>%
                                        </div-->
                                        <div class="old currency">
                                            <?=HogartHelpers::woPrice($arResult["PRICES"]["BASE"]["PRINT_VALUE"]);?>
                                            <i class="fa fa-<?=strtolower($arResult["PRICES"]["BASE"]["CURRENCY"])?>" aria-hidden="true"></i>
                                        </div>
                                        <div class="sup small">* Цена указана с учетом скидки клиента</div>
                                    </div>
                                <? elseif(!$USER->IsAuthorized()): ?>
                                    <small style="display: block">
                                        Для покупки необходимо <a class="js-popup-open" data-popup="#popup-login" href="javascript:void(0)">авторизоваться</a>
                                    </small>
                                <? endif; ?>
                                <!---->
                            </div>
                            <div class="col-md-6">
                                <? if($arResult["CATALOG_QUANTITY"] > 0): ?>
                                    <div class="">
                                        <div class="icon-carTon grid-hide">
                                            <div class="quantity quantity-success">В
                                                наличии<? if ($USER->IsAuthorized()): ?> <span><?= $arResult["CATALOG_QUANTITY"]; ?>
                                                    <?=$arResult['CATALOG_MEASURE_NAME']?>.</span><? endif; ?></div>
                                        </div>
                                    </div>
                                <? else: ?>
                                    <div class="">
                                        <div class="quantity quantity-fail text-nowrap">
                                            <i class="fa fa-truck" aria-hidden="true"></i> Под заказ
                                            <? if(!empty($arResult["PROPERTIES"]["delivery_period"]["VALUE"])): ?>
                                                <br>
                                                <span>Срок поставки <?=$arResult["PROPERTIES"]["delivery_period"]["VALUE"]?> <?=number($arResult["PROPERTIES"]["delivery_period"]["VALUE"], array('день',
                                                        'дня',
                                                        'дней'))?></span>
                                            <? endif; ?>
                                        </div>
                                    </div>
                                    <? if(!empty($arResult["PROPERTIES"]["supply"]["VALUE"])): ?>
                                        <div class="">
                                            <div class="icon-sheTon grid-hide">
                                                ожидаемое поступление <br>
                                                <?=FormatDate('d F',
                                                    MakeTimeStamp($arResult["PROPERTIES"]["supply"]["VALUE"]));?>
                                            </div>
                                        </div>
                                    <? endif; ?>
                                <? endif; ?>
                                <? if($USER->IsAuthorized()): ?>
                                    <div class="">
                                        <?
                                        $class_pop = '';
                                        $attr_pop = '';
                                        ?>
                                        <?
                                        if (!$USER->IsAuthorized()) {
                                            $class_pop = 'js-popup-open';
                                            $attr_pop = 'data-popup="#popup-msg-product"';
                                        }
                                        ?>
                                        <a id="<?= $arResult['BUY_URL'] ?>"
                                           class="buy-btn btn btn-primary <?= $class_pop ?>" <?= $attr_pop ?>
                                           href="javascript:void(0)" rel="nofollow">
                                            <i class="fa fa-shopping-cart" aria-hidden="true"></i> Купить
                                        </a>
                                    </div>
                                <? endif; ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <span class="brand-title">
                                    <? if (!empty($arResult["DISPLAY_PROPERTIES"]['collection'])): ?>
                                        <?= $arResult["DISPLAY_PROPERTIES"]['collection']['LINK_ELEMENT_VALUE'][$arResult["DISPLAY_PROPERTIES"]['collection']['VALUE']]['NAME'] ?>,
                                    <? endif; ?>
                                    <?= $arResult["DISPLAY_PROPERTIES"]['brand']['DISPLAY_VALUE'] ?>
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="detail-text">
                                    <? if (!empty($arResult["DISPLAY_PROPERTIES"]["collection"]["PREVIEW_TEXT"])): ?>
                                        <div class="preview"><?= $arResult["DISPLAY_PROPERTIES"]["collection"]["PREVIEW_TEXT"] ?></div>
                                    <? endif; ?>
                                    <? if (!empty($arResult["DISPLAY_PROPERTIES"]["collection"]["DETAIL_TEXT"])): ?>
                                        <? if(!empty($arResult["DISPLAY_PROPERTIES"]["collection"]["PREVIEW_TEXT"])): ?>
                                            <div class="more">Далее</div>
                                        <? endif; ?>
                                        <div class="detail"><?= $arResult["DISPLAY_PROPERTIES"]["collection"]["DETAIL_TEXT"] ?></div>
                                    <? endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="products-similar-tabs">
            <ul class="nav nav-tabs" role="tablist">
                <? $tab_active = null; ?>
                <? if(isset($arResult["buy_with_this"]["ITEMS"])): ?>
                    <li role="presentation" class="active">
                        <a href="#buy_with_this" aria-controls="buy_with_this" role="tab" data-toggle="tab">С этим товаром покупают</a>
                    </li>
                    <? $tab_active = 'buy_with_this'; ?>
                <? endif; ?>
                <? if(isset($arResult["related"]["ITEMS"])): ?>
                    <li role="presentation" class="<?= (!$tab_active ? 'active' : '') ?>">
                        <a href="#related" aria-controls="related" role="tab" data-toggle="tab">Сопутствующие товары</a>
                    </li>
                    <? $tab_active = 'related'; ?>
                <? endif; ?>
                <? if(isset($arResult["alternative"]["ITEMS"])): ?>
                    <li role="presentation" class="<?= (!$tab_active ? 'active' : '') ?>">
                        <a href="#alternative" aria-controls="alternative" role="tab" data-toggle="tab">Альтернативные товары</a>
                    </li>
                    <? $tab_active = 'alternative'; ?>
                <? endif; ?>
                <? if(isset($arResult["this_collection"]["ITEMS"])): ?>
                    <li role="presentation" class="<?= (!$tab_active ? 'active' : '') ?>">
                        <a href="#this_collection" aria-controls="this_collection" role="tab" data-toggle="tab">Еще из этой коллекции</a>
                    </li>
                    <? $tab_active = 'this_collection'; ?>
                <? endif; ?>
            </ul>
            <div class="tab-content">
                <? if(isset($arResult["this_collection"]["ITEMS"])): ?>
                <div role="tabpanel" class="tab-pane <?= ($tab_active == 'this_collection' ? 'active' : '') ?>" id="this_collection">
                    <div id="com_<?= $collectionComponentId ?>">
                        <? $APPLICATION->ShowViewContent("related-collection-items") ?>
                    </div>
                </div>
                <? endif; ?>
            </div>
        </div>

        <!-- div class="products-similar-tabs inner">
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

            </div>
        </div -->

    </div>
    <div class="col-md-3 element-info">
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#properties" aria-controls="properties" role="tab" data-toggle="tab">Характеристики</a></li>
            <? if(count($arResult["DOCS"]) > 0): ?>
            <li role="presentation"><a href="#documents" aria-controls="documents" role="tab" data-toggle="tab">Документация</a></li>
            <? endif; ?>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="properties">
                <div class="detail-text container-fluid">
                    <?= $arResult["DETAIL_TEXT"] ?>
                </div>
                <div class="features container-fluid">
                    <div class="feature dotted">
                        <span class="text-left">Ед. изм.</span>
                        <span class="text-right"><?= $arResult["CATALOG_MEASURE_NAME"]?>.</span>
                    </div>

                    <? if($arResult["CUSTOM"]["BRAND_NAME"]): ?>
                        <div class="feature dotted">
                            <span class="text-left"><?=$arResult["DISPLAY_PROPERTIES"]["brand"]["NAME"]?></span>
                            <span class="text-right"><?=$arResult["CUSTOM"]["BRAND_NAME"]?></span>
                        </div>
                    <? endif; ?>

                    <? if(!empty($arResult['DISPLAY_PROPERTIES']['collection']['LINK_ELEMENT_VALUE'])): ?>
                        <? $collection_element = current($arResult['DISPLAY_PROPERTIES']['collection']['LINK_ELEMENT_VALUE']) ?>
                        <div class="feature dotted">
                            <span class="text-left"><?=$arResult['DISPLAY_PROPERTIES']['collection']['NAME']?></span>
                            <span class="text-right"><?=$collection_element['NAME']?></span>
                        </div>
                    <? endif; ?>

                    <? foreach($arResult["PROPERTIES"] as $arProperty): ?>
                        <? if (empty($arProperty["VALUE"])) continue; ?>
                        <div class="feature dotted">
                            <span class="text-left"><?=$arProperty["NAME"]?></span>
                            <span class="text-right"><?=$arProperty["VALUE"]?></span>
                        </div>
                    <? endforeach; ?>
                </div>
            </div>
            <? if(count($arResult["DOCS"]) > 0): ?>
            <div role="tabpanel" class="tab-pane" id="documents">
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
            </div>
            <? endif; ?>
        </div>
    </div>
</div>
