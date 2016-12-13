<?php
/**
 * @global $USER
 * @var array $arResult
 * @var array $related
 * @var string $componentId
 */

$controlsID = uniqid();
?>

<? if(!empty($related["PREV_LINK"]) || !empty($related["NEXT_LINK"])): ?>
    <div id="con-<?= $controlsID ?>" class="controls text-right">
        <? if (!empty($related["PREV_LINK"])): ?>
            <div class="prev">
                <a href="<?= $related["PREV_LINK"] ?>" onclick="BX.ajax.insertToNode('<?= $related["PREV_LINK"] ?>', 'com_<?= $componentId ?>'); return false;">
                    <i class="fa fa-arrow-circle-o-left"></i>
                </a>
            </div>
        <? endif; ?>
        <? if (!empty($related["NEXT_LINK"])): ?>
            <div class="next">
                <a href="<?= $related["NEXT_LINK"] ?>" onclick="BX.ajax.insertToNode('<?= $related["NEXT_LINK"] ?>', 'com_<?= $componentId ?>'); return false;">
                    <i class="fa fa-arrow-circle-o-right"></i>
                </a>
            </div>
        <? endif; ?>
    </div>
<? endif; ?>


<ul data-control="#con-<?= $controlsID ?>" class="row related-products">
    <? foreach ($related['ITEMS'] as $arProduct): ?>
        <li class="col-lg-3 col-md-4 col-sm-6 this-collection-item">
            <div>
                <span class="perechen-img">
                    <a href="<?= $arProduct["DETAIL_PAGE_URL"] ?>">
                        <?
                        $arImage = null;
                        if (!empty($arProduct["PICTURE"]['SRC']) && file_exists(realpath($_SERVER["DOCUMENT_ROOT"] . $arProduct["PICTURE"]["SRC"]))) {
                            $arImage = $arProduct["PICTURE"];
                        }
                        if (!empty($arImage)) {
                            $file = CFile::ResizeImageGet($arImage, array("width" => 213, "height" => 160), BX_RESIZE_IMAGE_EXACT, true);
                            $pic = $file['src'];
                        } else {
                            if (!file_exists($_SERVER["DOCUMENT_ROOT"] . "/images/project_no_img_400x160.jpg")) {
                                $file = $_SERVER["DOCUMENT_ROOT"] . "/images/project_no_img_400x160.jpg";
                                CFile::ResizeImageFile($_SERVER["DOCUMENT_ROOT"] . "/images/project_no_img.jpg", $file, array("width" => 213, "height" => 160));
                            }
                            $pic = "/images/project_no_img_400x160.jpg";
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
                        <div class="col-md-12 related-product-name">
                            <a href="<?= $arProduct["DETAIL_PAGE_URL"] ?>">
                                <span class="related-product-name">
                                    <h3><?= $arProduct["NAME"] ?></h3>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
                <br>
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
                        <? if($arProduct["RELATED_SECTION"] == "this_collection"): ?>
                            <?
                                $showPropertyIndex = 0;
                                $skipProperties = array("collection", "sku", "brand", "days_till_receive", "warehouse");
                            ?>
                            <? foreach ($arProduct['SHOW_PROPS'] as $i => $arProperty): ?>
                                <? if (in_array($arProperty["CODE"], $skipProperties)): ?>
                                    <? continue; ?>
                                <? endif; ?>
                                <li class="note">
                                    <div class="dotted">
                                        <span class="text-left"><?= $arProperty['NAME'] ?></span>
                                        <span class="text-right"><span class="pr"><?= $arProperty["VALUE"] ?></span></span>
                                    </div>
                                </li>
                                <? $showPropertyIndex += 1; ?>
                                <? if ($showPropertyIndex >= 3): ?><? break ?><? endif; ?>
                            <? endforeach; ?>
                        <? endif; ?>
                    </ul>
                </div>
                <div class="price-cnt<? if ($USER->IsAuthorized()): ?> auth-block<? endif; ?>">
                    <div class="row vertical-align">
                        <div class="col-md-6 text-nowrap">
                            <div class="quantity-wrapper">
                                <? if ($arProduct["CATALOG_QUANTITY"] > 0): ?>
                                    <div class="quantity quantity-success line <? if ($USER->IsAuthorized()): ?> line2<? endif; ?>">
                                        В наличии <? if ($USER->IsAuthorized()): ?><span><?= $arProduct["CATALOG_QUANTITY"]; ?> <?=$arProduct['CATALOG_MEASURE_NAME']?>.</span><? endif; ?>
                                    </div>
                                <? else: ?>
                                    <div class="quantity quantity-fail text-nowrap">
                                        <i class="fa fa-truck" aria-hidden="true"></i> Заказ
                                    </div>
                                <? endif; ?>

                                <? if ($USER->IsAuthorized() && ($arProduct["CATALOG_QUANTITY"] > 0 || !empty($arProduct["PROPERTY_DAYS_TILL_RECEIVE"]["VALUE"]))): ?>
                                    <div class="stocks-wrapper">
                                        <div class="triangle-with-shadow"></div>
                                        <div class="stock-header">
                                            <?= $arProduct["NAME"]?>, <?= $arProduct['BRAND_NAME'] ?> <?= $arProduct["PROPERTY_SKU_VALUE"] ?>
                                        </div>
                                        <div class="stock-items">
                                            <div class="stock-items-table">
                                                <? foreach ($arResult['STORES'] as $store_id => $store): ?>
                                                    <? if (!$arProduct['STORE_AMOUNTS'][$store_id]['is_visible'] && empty($arProduct["PROPERTY_DAYS_TILL_RECEIVE"]["VALUE"])) continue; ?>
                                                    <div class="stock-item">
                                                <span class="stock-name h4 text-left">
                                                    <?= $store["TITLE"]?>
                                                </span>
                                                        <span class="quantity">
                                                    <div>
                                                        <div class="amount h4">
                                                            <?= (int)$arProduct['STORE_AMOUNTS'][$store_id]['stock'] ?> <?=$arProduct['CATALOG_MEASURE_NAME']?>.
                                                        </div>
                                                        <div class="desc h6">
                                                            Остаток
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="amount h4">
                                                            <?= (int)$arItem['STORE_AMOUNTS'][$store_id]['in_reserve'] ?> <?=$arProduct['CATALOG_MEASURE_NAME']?>.
                                                        </div>
                                                        <div class="desc h6">
                                                            Резерв
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="amount h4">
                                                            <?= (int)$arProduct['STORE_AMOUNTS'][$store_id]['in_transit'] ?> <?=$arProduct['CATALOG_MEASURE_NAME']?>.
                                                        </div>
                                                        <div class="desc h6">
                                                            Ожидается
                                                        </div>
                                                    </div>
                                                            <? if (!empty($arProduct["PROPERTY_DAYS_TILL_RECEIVE"]["VALUE"])): ?>
                                                                <div>
                                                        <div class="amount h4">
                                                            <i class="glyphicon glyphicon-time"></i>
                                                            <?= (int)$arProduct["PROPERTY_DAYS_TILL_RECEIVE"]["VALUE"] ?> дн.
                                                        </div>
                                                        <div class="desc h6">
                                                            Срок поставки
                                                        </div>
                                                    </div>
                                                            <? endif; ?>
                                                </span>
                                                    </div>
                                                <? endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                <? endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="row vertical-align">
                        <div class="col-md-6">
                            <div class="price currency-<?= strtolower($arProduct["PRICES"]["BASE"]["CURRENCY"]) ?> text-nowrap">
                            <? if ($USER->IsAuthorized() and isset($arProduct["PRICES"]["BASE"])): ?>
                                <? if (isset($arProduct["PRICES"]["BASE"]["DISCOUNT_VALUE"])): ?>
                                    <?= \Hogart\Lk\Helper\Template\Money::show($arProduct["PRICES"]["BASE"]["DISCOUNT_VALUE"]) ?>
                                <? elseif (isset($arProduct["PRICES"]["BASE"]["VALUE"])): ?>
                                    <?= \Hogart\Lk\Helper\Template\Money::show($arProduct["PRICES"]["BASE"]["VALUE"]) ?>
                                <? else: ?>
                                    <?= \Hogart\Lk\Helper\Template\Money::show($arProduct["CATALOG_PRICE_1"]) ?>
                                <? endif; ?>
                            <? else: ?>
                                <?= \Hogart\Lk\Helper\Template\Money::show($arProduct["CATALOG_PRICE_1"]) ?>
                            <? endif; ?>
                                <i class="fa fa-<?=strtolower($arResult["PRICES"]["BASE"]["CURRENCY"])?>" aria-hidden="true"></i>
                            </div>
                            <!--Только для авторизованных-->
                            <? if ($USER->IsAuthorized() && !empty($arProduct["PRICES"]["BASE"]["DISCOUNT_DIFF_PERCENT"])): ?>
                                <div class="grid-hide discount">
                                    <?= $arProduct["PRICES"]["BASE"]["DISCOUNT_DIFF_PERCENT"] ?>%
                                </div>
                            <? endif; ?>
                            <!---->
                        </div>
                    </div>

                    <!--Только для авторизованных-->
                    <? if ($USER->IsAuthorized()): ?>
                        <div class="row <?= (!empty($arProduct["PRICES"]["BASE"]["DISCOUNT_DIFF_PERCENT"]) ? "vertical-align" : "") ?>">
                            <? if (!empty($arProduct["PRICES"]["BASE"]["DISCOUNT_DIFF_PERCENT"])): ?>
                                <div class="col-sm-8">
                                    <div class="info-block text-nowrap">
                                        <div class="old currency">
                                            <?= \Hogart\Lk\Helper\Template\Money::show($arProduct["PRICES"]["BASE"]["VALUE"]); ?>
                                            <i class="fa fa-<?=strtolower($arProduct["PRICES"]["BASE"]["CURRENCY"])?>" aria-hidden="true"></i>
                                        </div>
                                        <div class="discount">
                                            <?= $arProduct["PRICES"]["BASE"]["DISCOUNT_DIFF_PERCENT"] ?>%
                                        </div>
                                    </div>
                                </div>
                            <? endif; ?>
                            <div class="col-sm-4 text-right pull-right">
                                <?= \Hogart\Lk\Helper\Template\Cart::Link(
                                    '<i class="fa fa-cart-plus" aria-hidden="true"></i>',
                                    [
                                        'item_id' => $arProduct['ID'],
                                        'count' => '1'
                                    ],
                                    'class="black buy"'
                                ) ?>
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
                                <?= \Hogart\Lk\Helper\Template\Cart::Link(
                                    '<i class="fa fa-cart-plus" aria-hidden="true"></i> Купить',
                                    ['item_id' => $arProduct['ID']],
                                    'class="buy-btn btn btn-primary ' . $class_pop . ' ' . $attr_pop . '"'
                                ) ?>
                            </div>
                        </div>
                    <? endif; ?>
                    <!---->
                </div>
            </div>
        </li>
    <? endforeach; ?>
</ul>
