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

use Hogart\Lk\Helper\Template\Account;


$collectionComponentId = CAjax::GetComponentID("bitrix:catalog.element", "", "collection");
$buyWithThisComponentId = CAjax::GetComponentID("bitrix:catalog.element", "", "buy_with_this");
$relatedComponentId = CAjax::GetComponentID("bitrix:catalog.element", "", "related");
$alternativeComponentId = CAjax::GetComponentID("bitrix:catalog.element", "", "alternative");
?>

<?php
$this->SetViewTarget("related-collection-items");
$componentId = $collectionComponentId;
$related = $arResult["this_collection"];
include __DIR__ . "/related-items.php";
$this->EndViewTarget();
?>

<? if (!empty($_REQUEST["collection"])): ?>
    <? $APPLICATION->RestartBuffer() ?>
    <? $APPLICATION->ShowViewContent("related-collection-items") ?>
    <? exit; ?>
<? endif; ?>

<?
$this->SetViewTarget("buy-with-this-items");
$componentId = $buyWithThisComponentId;
$related = $arResult["buy_with_this"];
include __DIR__ . "/related-items.php";
$this->EndViewTarget();
?>

<? if (!empty($_REQUEST["buy_with_this"])): ?>
    <? $APPLICATION->RestartBuffer() ?>
    <? $APPLICATION->ShowViewContent("buy-with-this-items") ?>
    <? exit; ?>
<? endif; ?>

<?
$this->SetViewTarget("related-items");
$componentId = $relatedComponentId;
$related = $arResult["related"];
include __DIR__ . "/related-items.php";
$this->EndViewTarget();
?>

<? if (!empty($_REQUEST["related"])): ?>
    <? $APPLICATION->RestartBuffer() ?>
    <? $APPLICATION->ShowViewContent("related-items") ?>
    <? exit; ?>
<? endif; ?>

<?
$this->SetViewTarget("alternative-items");
$componentId = $alternativeComponentId;
$related = $arResult["alternative"];
include __DIR__ . "/related-items.php";
$this->EndViewTarget();
?>

<? if (!empty($_REQUEST["alternative"])): ?>
    <? $APPLICATION->RestartBuffer() ?>
    <? $APPLICATION->ShowViewContent("alternative-items") ?>
    <? exit; ?>
<? endif; ?>

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
                        <? if(Account::isAuthorized() && !empty($arResult["PRICES"]["BASE"]["DISCOUNT_DIFF_PERCENT"])): ?>
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
                                    <? if(Account::isAuthorized() && !empty($arResult["PRICES"]["BASE"]["DISCOUNT_DIFF_PERCENT"])): ?>
                                        <?= \Hogart\Lk\Helper\Template\Money::show($arResult["PRICES"]["BASE"]["DISCOUNT_VALUE"]) ?>
                                    <? else: ?>
                                        <?= \Hogart\Lk\Helper\Template\Money::show($arResult["PRICES"]["BASE"]["VALUE"]) ?>
                                    <? endif; ?>
                                    <i class="fa fa-<?=strtolower($arResult["PRICES"]["BASE"]["CURRENCY"])?>" aria-hidden="true"></i>
                                    <? if(Account::isAuthorized() && !empty($arResult["PRICES"]["BASE"]["DISCOUNT_DIFF_PERCENT"])): ?>
                                        <sup>*</sup>
                                    <? endif; ?>
                                </div>
                                <? endif; ?>
                                
                                <!--Только для авторизованных-->
                                <? if(Account::isAuthorized() && !empty($arResult["PRICES"]["BASE"]["DISCOUNT_DIFF_PERCENT"])): ?>
                                    <div class="info-block">
                                        <div class="old currency">
                                            <?=HogartHelpers::woPrice($arResult["PRICES"]["BASE"]["PRINT_VALUE"]);?>
                                            <i class="fa fa-<?=strtolower($arResult["PRICES"]["BASE"]["CURRENCY"])?>" aria-hidden="true"></i>
                                        </div>
                                        <div class="discount" style="display: inline-block">
                                            -<?=$arResult["PRICES"]["BASE"]["DISCOUNT_DIFF_PERCENT"]?>%
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
                                            <div class="quantity quantity-success">
                                                В наличии
                                                <? if (Account::isAuthorized()):?>
                                                    <span>
                                                        <?= $arResult["CATALOG_QUANTITY"]; ?>
                                                        <?=$arResult['CATALOG_MEASURE_NAME']?>.
                                                    </span>
                                                <? endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <? else: ?>
                                    <div class="">
                                        <div class="quantity quantity-fail text-nowrap">
                                            <i class="fa fa-truck" aria-hidden="true"></i> Заказ
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
                                <? if(Account::isAuthorized()): ?>
                                    <div class="">
                                        <?
                                        $class_pop = '';
                                        $attr_pop = '';
                                        ?>
                                        <?= \Hogart\Lk\Helper\Template\Cart::Link(
                                            '<i class="fa fa-cart-plus" aria-hidden="true"></i> Купить',
                                            ['item_id' => $arResult['ID']],
                                            'class="buy-btn btn btn-primary ' . $class_pop . ' ' . $attr_pop . '"'
                                        ) ?>
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
                        <a href="#related" aria-controls="related" role="tab" data-toggle="tab">Принадлежности</a>
                    </li>
                    <? $tab_active = $tab_active ? : 'related'; ?>
                <? endif; ?>
                <? if(isset($arResult["alternative"]["ITEMS"])): ?>
                    <li role="presentation" class="<?= (!$tab_active ? 'active' : '') ?>">
                        <a href="#alternative" aria-controls="alternative" role="tab" data-toggle="tab">Аналоги</a>
                    </li>
                    <? $tab_active = $tab_active ? : 'alternative'; ?>
                <? endif; ?>
                <? if(isset($arResult["this_collection"]["ITEMS"])): ?>
                    <li role="presentation" class="<?= (!$tab_active ? 'active' : '') ?>">
                        <a href="#this_collection" aria-controls="this_collection" role="tab" data-toggle="tab">Еще из этой коллекции</a>
                    </li>
                    <? $tab_active = $tab_active ? : 'this_collection'; ?>
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

                <? if(isset($arResult["buy_with_this"]["ITEMS"])): ?>
                <div role="tabpanel" class="tab-pane <?= ($tab_active == 'buy_with_this' ? 'active' : '') ?>" id="buy_with_this">
                    <div id="com_<?= $buyWithThisComponentId ?>">
                        <? $APPLICATION->ShowViewContent("buy-with-this-items") ?>
                    </div>
                </div>
                <? endif; ?>

                <? if(isset($arResult["related"]["ITEMS"])): ?>
                <div role="tabpanel" class="tab-pane <?= ($tab_active == 'related' ? 'active' : '') ?>" id="related">
                    <div id="com_<?= $relatedComponentId ?>">
                        <? $APPLICATION->ShowViewContent("related-items") ?>
                    </div>
                </div>
                <? endif; ?>

                <? if(isset($arResult["alternative"]["ITEMS"])): ?>
                    <div role="tabpanel" class="tab-pane <?= ($tab_active == 'alternative' ? 'active' : '') ?>" id="alternative">
                        <div id="com_<?= $alternativeComponentId ?>">
                            <? $APPLICATION->ShowViewContent("alternative-items") ?>
                        </div>
                    </div>
                <? endif; ?>
            </div>
        </div>

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
