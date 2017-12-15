<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
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
use \Hogart\Lk\Helper\Template\Account;
?>
<?
if (count($arResult["ITEMS"])==0)
LocalRedirect('/catalog/index.php');
?>
<? if(!empty($arResult['PARENT_PARENT_SECTION']["UF_PRICE"])): ?>
<div class="text-right">
    <? $priceFileMeta = CFile::MakeFileArray($arResult['PARENT_PARENT_SECTION']["UF_PRICE"]) ?>
    <span class="price-list">
        <a href="<?=CFile::GetPath($arResult['PARENT_PARENT_SECTION']["UF_PRICE"]); ?>" class="download">
            <i class="fa fa-download"></i> <span><?=$arResult['PARENT_PARENT_SECTION']["UF_PRICE_LABEL"]?></span>
        </a>
        <span class="file-metadata">
            <?= strtoupper(explode('/', $priceFileMeta['type'])[1]) ?>, <?= convert($priceFileMeta['size']) ?>
        </span>
    </span>
</div>
<? endif; ?>
<ul class="row perechen-produts js-target-perechen <?=$arParams['VIEW_TYPE']?> <? if ($arParams["IS_TABLE_VIEW"]): ?>table-view<? endif; ?>">
<? $collectionId = null; $brandId = null; $table_sort = null; $sectionId = null;
$mobileHtml = ($arParams["IS_TABLE_VIEW"]) ? '<div class="col-md-9 collection-table-mobile"><ul class="row perechen-produts js-target-perechen list ">' : '<div class="col-md-9"><ul class="row perechen-produts js-target-perechen list ">'; ?>
<? foreach ($arResult["ITEMS"] as $i => $arItem):?>
        <?
        $rsFile = CFile::GetByID($arItem['PROPERTIES']['photos']['VALUE'][0]);
        $arFile = $rsFile->Fetch();
        ?>

    <? if ($arParams["IS_TABLE_VIEW"]): ?>
        <? if (!empty($collectionId) && $collectionId != $arItem["PROPERTIES"]["collection"]["VALUE"]): ?>
            </ul></div>
            <?= $mobileHtml . '</ul></div>'; $mobileHtml = '<div class="col-md-9 collection-table-mobile"><ul class="row perechen-produts js-target-perechen list ">'; ?>
            <!-- закрываем блок коллекции <?= $collectionId ?> -->
            <?  $collectionId = null; $table_sort = null; ?>
        <? endif; ?>
        <? if (!empty($brandId) && $brandId != $arItem["PROPERTIES"]["brand"]["VALUE"]): ?>

            <? if ($brand_block): ?>
                </ul>
                <!-- конец блока товаров без коллекции -->
                <? $brand_block = null; ?>
            <? endif; ?>

            </div></li>
            <!-- закрываем блок бренда <?= $brandId ?> -->
        <? endif; ?>

        <? ob_start(); ?>
        <li class="brand-collection-header">
            <span class="cell"><?= $arItem["PROPERTIES"]["sku"]["NAME"] ?></span>
            <span class="cell" style="width: 100%">Наименование</span>
            <? foreach ($arItem["PROPERTIES"] as $propertyName => $arProperty): ?>
                <? if ($arProperty["DISPLAY_EXPANDED"] == "Y"): ?>
                    <span class="cell"><?= $arProperty["NAME"]; ?></span>
                <? endif; ?>
            <? endforeach; ?>
            <span class="cell">Наличие</span>
            <span class="cell">Ед. изм.</span>
            <span class="cell">Цена <i class="fa fa-<?= strtolower($arItem["PRICES"]["BASE"]["CURRENCY"]) ?>" aria-hidden="true"></i></span>
            <? if (Account::isAuthorized()): ?>
                <span class="cell">%</span>
                <span class="cell">К заказу</span>
            <? endif; ?>
            <span class="cell"><i class="icon-cart"></i></span>
        </li>
        <? $brand_collection_header = ob_get_clean(); ?>

        <? if (!empty($arItem["PROPERTIES"]["brand"]["VALUE"]) && $brandId != $arItem["PROPERTIES"]["brand"]["VALUE"]): ?>
            <?
                $brandId = $arItem["PROPERTIES"]["brand"]["VALUE"];
            ?>
            <!-- бренд <?= $brandId ?> -->
            <li class="brand-table">
                <div data-brand-wrapper="<?= $brandId?>">
                    <div class="caption">
                        <div class="brand-name"><i class="fa"></i><?= $arResult["ALL_BRANDS"][$brandId]["NAME"] ?></div>
                    </div>
                <? if (empty($arItem["PROPERTIES"]["collection"]["VALUE"])): ?>
                    <!-- блок товаров без коллекции -->
                    <ul data-brand="<?= $brandId?>"><?= $brand_collection_header ?>
                    <? $brand_block = true; ?>
                <? endif; ?>
        <? endif; ?>

        <? if (!empty($arItem["PROPERTIES"]["collection"]["VALUE"]) && $collectionId != $arItem["PROPERTIES"]["collection"]["VALUE"]): ?>
            <?
                $collectionId = $arItem["PROPERTIES"]["collection"]["VALUE"];
                $collection = $arResult["ALL_COLLS"][$arItem["PROPERTIES"]["collection"]["VALUE"]];
            ?>

            <? if ($brand_block): ?>
                </ul>
            </div>
            <!-- конец блока товаров без коллекции -->
            <? $brand_block = false; ?>
            <? endif; ?>

            <!-- коллекция <?= $collectionId ?> -->
            <div class="collection-table">
            <ul data-collection="<?= $collectionId ?>">
            <li class="caption">
                <? if (!empty($collection["DETAIL_PICTURE"])): ?>
                    <div class="collection-image">
                        <?
                        $big = CFile::GetFileArray($collection["DETAIL_PICTURE"]);
                        $file = CFile::ResizeImageGet(
                            $collection["DETAIL_PICTURE"], array("width" => 400, "height" => 300), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                        ?>
                        <img
                            data-big-img="<?= $big["SRC"] ?>"
                            class="js-popup-open-img"
                            src="<?= $file['src'] ?>"
                            alt="<?= $collection["NAME"] ?>">
                    </div>
                <? endif; ?>
                <div class="collection-description">
                    <div class="collection-title"><?= $collection["NAME"] ?></div>
                    <div class="collection-text">
                        <? if (!empty($collection["PREVIEW_TEXT"])): ?>
                            <div class="preview">
                                <?= $collection["PREVIEW_TEXT"] ?>
                                <? if (!empty($collection["DETAIL_TEXT"])): ?>
                                    <a
                                            href="#more-collection-<?= $collectionId ?>"
                                            class="more-collection-desc"
                                            role="button"
                                            data-toggle="collapse"
                                            aria-expanded="false">Далее&nbsp;>>
                                    </a>
                                <? endif; ?>
                            </div>
                            <? if (!empty($collection["DETAIL_TEXT"])): ?>
                                <div id="more-collection-<?= $collectionId ?>" class="collapse">
                                    <?= $collection["DETAIL_TEXT"] ?>
                                </div>
                            <? endif; ?>
                        <? endif; ?>
                    </div>
                </div>
            </li>
            <?= $brand_collection_header ?>
            <? $brand_collection_header = "";?>
        <? endif; ?>

        <? if (!empty($arParams["TABLE_SORT"]) && $table_sort != $arItem["PROPERTIES"][$arParams["TABLE_SORT"]["CODE"]]["VALUE"]): ?>
        <? $table_sort = $arItem["PROPERTIES"][$arParams["TABLE_SORT"]["CODE"]]["VALUE"]; ?>
        <?= $brand_collection_header ?>
        <li class="brand-collection-subtable-header">
            <span><?= $table_sort ?> <?= trim(substr($arItem["PROPERTIES"][$arParams["TABLE_SORT"]["CODE"]]["NAME"], strpos($arItem["PROPERTIES"][$arParams["TABLE_SORT"]["CODE"]]["NAME"], ",") + 1)) ?></span>
        </li>
        <? endif; ?>

        <li <? if (!empty($collectionId)):?> data-collection-item-id="<?= $arItem["ID"] ?>"<? endif; ?> <? if (!empty($brandId) && empty($collectionId)):?> data-brand-item-id="<?= $arItem["ID"] ?>"<? endif; ?> >
            <span class="cell"><a href="<?= $arItem["DETAIL_PAGE_URL"] ?>"><?= $arItem["PROPERTIES"]["sku"]["VALUE"] ?></a></span>
            <span class="cell"><a href="<?= $arItem["DETAIL_PAGE_URL"] ?>"><?= $arItem["NAME"] ?></a></span>
            <? foreach ($arItem["PROPERTIES"] as $propertyName => $arProperty): ?>
                <? if ($arProperty["DISPLAY_EXPANDED"] == "Y"): ?>
                    <span class="cell<?= ($arProperty["PROPERTY_TYPE"] == "N" ? " text-center " : "")?>"><?= $arProperty["VALUE"]; ?></span>
                <? endif; ?>
            <? endforeach; ?>
            <span class="cell quantity text-center <? if ($arItem["CATALOG_QUANTITY"] > 0): ?>quantity--available<? endif; ?>">
                <div class="<? if (Account::isAuthorized()):?>quantity-wrapper<? endif; ?>">
                <? if ($arItem["CATALOG_QUANTITY"] > 0): ?>
                    <? if (!Account::isAuthorized()): ?>
                        <i class="fa fa-check" aria-hidden="true"></i>
                    <? endif; ?>
                <? else: ?>
                    <span style="white-space: nowrap"><i class="fa fa-close" aria-hidden="true"></i> Заказ</span>
                <? endif; ?>

                <? if (Account::isAuthorized() && $arItem["CATALOG_QUANTITY"] > 0): ?>
                    <span style="white-space: nowrap"><?= $arItem["CATALOG_QUANTITY"]; ?></span>
                <? endif; ?>
                    <? if (Account::isAuthorized() && ($arItem["CATALOG_QUANTITY"] > 0 || !empty($arItem["PROPERTIES"]["days_till_receive"]["VALUE"]))): ?>
                    <div class="stocks-wrapper">
                        <div class="triangle-with-shadow"></div>
                        <div class="stock-header">
                            <?= $arItem["NAME"]?>, <?= $arResult["ALL_BRANDS"][$arItem["PROPERTIES"]["brand"]["VALUE"]]['NAME'] ?> <?= $arItem["PROPERTIES"]["sku"]["VALUE"] ?>
                        </div>
                        <div class="stock-items">
                            <div class="stock-items-table">
                            <? foreach ($arResult['STORES'] as $store_id => $store): ?>
                                <? if (!$arItem['STORE_AMOUNTS'][$store_id]['is_visible'] && empty($arItem["PROPERTIES"]["days_till_receive"]["VALUE"])) continue; ?>
                                <div class="stock-item">
                                    <span class="stock-name h4 text-left">
                                        <?= $store["TITLE"]?>
                                    </span>
                                    <span class="quantity">
                                        <div>
                                            <div class="amount h4">
                                                <?= (int)$arItem['STORE_AMOUNTS'][$store_id]['stock'] ?> <?=$arItem['CATALOG_MEASURE_NAME']?>.
                                            </div>
                                            <div class="desc h6">
                                                Остаток
                                            </div>
                                        </div>
                                        <div>
                                            <div class="amount h4">
                                                <?= (int)$arItem['STORE_AMOUNTS'][$store_id]['in_reserve'] ?> <?=$arItem['CATALOG_MEASURE_NAME']?>.
                                            </div>
                                            <div class="desc h6">
                                                Резерв
                                            </div>
                                        </div>
                                        <div>
                                            <div class="amount h4">
                                                <?= (int)$arItem['STORE_AMOUNTS'][$store_id]['in_transit'] ?> <?=$arItem['CATALOG_MEASURE_NAME']?>.
                                            </div>
                                            <div class="desc h6">
                                                Ожидается
                                            </div>
                                        </div>
                                        <? if (!empty($arItem["PROPERTIES"]["days_till_receive"]["VALUE"])): ?>
                                        <div>
                                            <div class="amount h4">
                                                <i class="glyphicon glyphicon-time"></i>
                                                <?= (int)$arItem["PROPERTIES"]["days_till_receive"]["VALUE"] ?> дн.
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
            </span>
            <span class="cell text-center"><?=$arItem['CATALOG_MEASURE_NAME']?>.</span>
            <span class="cell text-center price currency-<?= strtolower($arItem["PRICES"]["BASE"]["CURRENCY"]) ?>">
                <? if (Account::isAuthorized() && !empty($arItem["PRICES"]["BASE"]["DISCOUNT_DIFF_PERCENT"])): ?>
                    <?= \Hogart\Lk\Helper\Template\Money::show($arItem["PRICES"]["BASE"]["DISCOUNT_VALUE"]) ?>
                <? else: ?>
                    <?= \Hogart\Lk\Helper\Template\Money::show($arItem["PRICES"]["BASE"]["VALUE"]) ?>
                <? endif; ?>
            </span>
            <? if (Account::isAuthorized()): ?>
            <span class="cell text-center">
                <? if (!empty($arItem["PRICES"]["BASE"]["DISCOUNT_DIFF_PERCENT"])): ?>
                <div class="grid-hide discount">
                    <?= $arItem["PRICES"]["BASE"]["DISCOUNT_DIFF_PERCENT"] ?>%
                </div>
                <? endif; ?>
            </span>
            <span class="cell text-center buy-quantity noselect" style="white-space: nowrap">
                <i class="fa fa-minus" onclick="Math.max(1, this.nextElementSibling.value--)"></i>
                <input type="text" name="quantity" value="1" />
                <i class="fa fa-plus" onclick="this.previousElementSibling.value++"></i>
            </span>
            <? endif; ?>

            <span class="cell text-center buy catalog-list__button">
                    <?
                    $class_pop = '';
                    $attr_pop = '';
                    if (!$USER->IsAuthorized()) {
                        $class_pop = 'js-popup-open';
                        $attr_pop = 'data-popup="#popup-msg-product"';
                    }
                    ?>
                    <?= \Hogart\Lk\Helper\Template\Cart::Link(
                        '<i class="fa fa-cart-plus" aria-hidden="true"></i>',
                        [
                            'item_id' => $arItem['ID'],
                            'count' => 'javascript:function (element) { return Math.max(1, $(element).parents("li:eq(0)").find("[name=\'quantity\']:input").val()); }'
                        ],
                        'class="black grid-hide ' . $class_pop . ' ' . $attr_pop . '"'
                    ) ?>
            </span>
        </li>

    <? //else: ?>
    <? endif; ?>
        <? if ($arParams["IS_TABLE_VIEW"]) {ob_start();} ?>
        <? if($sectionId != $arItem["~IBLOCK_SECTION_ID"] && $arParams["DEPTH_LEVEL"] == 2 && !$arParams["IS_FILTERED"]): ?>
            <? if(null !== $sectionId): ?>
                </ul>
                <ul class="row perechen-produts js-target-perechen <?=$arParams['VIEW_TYPE']?> <? if ($arParams["IS_TABLE_VIEW"]): ?>table-view<? endif; ?>">
            <? endif; ?>
        <li class="col-md-12 caption" data-section-id="<?= $arItem["~IBLOCK_SECTION_ID"] ?>" data-section-name="<?= $arResult["SUBS"][$arItem["~IBLOCK_SECTION_ID"]]["NAME"] ?>">
            <span>
                <span class="section-name"><i class="fa"></i><?= $arResult["SUBS"][$arItem["~IBLOCK_SECTION_ID"]]["NAME"] ?></span>
                <? if ($arResult["SUBS"][$arItem["~IBLOCK_SECTION_ID"]]["ELEMENTS_COUNT"] > $arParams["SUB_SECTION_COUNT"]): ?>
                <span class="section-link">
                    <a href="<?= $arResult["SUBS"][$arItem["~IBLOCK_SECTION_ID"]]["SECTION_PAGE_URL"] ?>">Посмотреть все (<?= $arResult["SUBS"][$arItem["~IBLOCK_SECTION_ID"]]["ELEMENTS_COUNT"] ?>) <i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
                </span>
                <? endif; ?>
            </span>
        </li>
        <? $sectionId = $arItem["~IBLOCK_SECTION_ID"] ?>
        <? endif; ?>

        <? if($arParams["DEPTH_LEVEL"] > 2 && $brandId != $arItem["PROPERTIES"]["brand"]["VALUE"] && !$arParams["IS_FILTERED"]): ?>
        <? if(null !== $brandId): ?>
        </ul>
        <ul class="row perechen-produts catalog-list js-target-perechen <?=$arParams['VIEW_TYPE']?> <? if ($arParams["IS_TABLE_VIEW"]): ?>table-view<? endif; ?>">
        <? endif; ?>
        <? $brandId = $arItem["PROPERTIES"]["brand"]["VALUE"]; ?>

        <li class="col-md-12 col-sm-12 caption catalog-list__caption" data-brand-id="<?= $arItem["PROPERTIES"]["brand"]["VALUE"] ?>" data-brand-name="<?= $arResult["ALL_BRANDS"][$brandId]["NAME"] ?>">
            <span class="brand-name"><i class="fa"></i><?= $arResult["ALL_BRANDS"][$brandId]["NAME"] ?></span>
        </li>
        <? endif; ?>

        <li class="col-lg-3 col-md-4 col-sm-12 col-xs-12" data-item-id="<?= $i ?>">
            <div>
                <span class="perechen-img">
                    <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>">
                        <?
                        $arImage = null;
                        if (!empty($arItem["PREVIEW_PICTURE"]['SRC']) && file_exists(realpath($_SERVER["DOCUMENT_ROOT"] . $arItem["PREVIEW_PICTURE"]["SRC"]))) {
                            $arImage = $arItem["PREVIEW_PICTURE"];
                        }
                        elseif (!empty($arItem["DETAIL_PICTURE"]['SRC']) && file_exists(realpath($_SERVER["DOCUMENT_ROOT"] . $arItem["DETAIL_PICTURE"]["SRC"]))) {
                            $arImage = $arItem["DETAIL_PICTURE"];
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
                    <? if (!empty($arItem["PROPERTIES"]["sku"]["VALUE"])): ?>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="art">Артикул: <span><?= $arItem["PROPERTIES"]["sku"]["VALUE"] ?></span></div>
                            </div>
                        </div>
                    <? endif; ?>
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>"><h3><?= $arItem["NAME"] ?></h3></a>
                        </div>
                    </div>
                </div>
                <div class="param-cnt">
                    <ul class="param">
                        <?
                        $propertyName = 'brand';
                        $arProperty = $arItem['PROPERTIES'][$propertyName]
                        ?>
                        <li class="note">
                            <div class="dotted">
                                <span class="text-left"><?= $arProperty["NAME"] ?></span>
                                <span class="text-right">
                                    <a href="<?= $arResult["ALL_BRANDS"][$arProperty["VALUE"]]['DETAIL_PAGE_URL'] ?>">
                                        <span class="pr"><?= $arResult["ALL_BRANDS"][$arProperty["VALUE"]]['NAME'] ?></span>
                                    </a>
                                </span>
                            </div>
                        </li>
                        <? if(!empty($arItem['DISPLAY_PROPERTIES']["collection"]['NAME'])): ?>
                        <li class="note">
                            <div class="dotted">
                                <?$collectionElement = current($arItem['DISPLAY_PROPERTIES']["collection"]["LINK_ELEMENT_VALUE"]);?>
                                <span class="text-left"><?= $arItem['DISPLAY_PROPERTIES']["collection"]['NAME'] ?></span>
                                <span class="text-right"><?= $collectionElement['NAME'] ?></span>
                            </div>
                        </li>
                        <? endif; ?>
                        <? unset($arItem['PROPERTIES'][$propertyName]) ?>
                        <? unset($arItem['PROPERTIES']['collection']) ?>
                        <?$hiddenPropsExist=false;?>
                        <? foreach ($arItem["PROPERTIES"] as $propertyName => $arProperty): ?>
                            <? if (!empty($arProperty["VALUE"]) && $arProperty['SMART_FILTER'] == 'Y'): ?>
                                <? if (substr($propertyName, 0, 4) == "coll"): ?>
                                    <li class="note">
                                        <div class="dotted">
                                            <span class="text-left"><?= $arProperty["NAME"] ?></span>
                                            <span class="text-right"><?= $arResult["ALL_COLLS"][$arProperty["VALUE"]]['NAME'] ?></span>
                                        </div>
                                    </li>
                                <? elseif (substr($propertyName, 0, 3) != "pho"): ?>
                                    <li class="note"
                                        <? if ($arProperty['DISPLAY_EXPANDED'] != 'Y') { $hiddenPropsExist=true;?>style="display: none"<? } ?>>
                                        <div class="dotted">
                                            <span class="text-left"><?= $arProperty["NAME"] ?></span>
                                            <span class="text-right"><?= $arProperty["VALUE"] ?></span>
                                        </div>
                                    </li>
                                <? endif; ?>
                            <? endif; ?>
                        <? endforeach; ?>
                        <?if ($hiddenPropsExist) {?>
                            <!-- li class="open">развернуть</li -->
                        <?}?>
                    </ul>
                </div>
                <div class="price-cnt catalog-list__price<? if ($USER->IsAuthorized()): ?> auth-block<? endif; ?>">
                    <div class="row vertical-align">
                        <div class="col-md-6 col-sm-12">
                            <div class="price currency-<?= strtolower($arItem["PRICES"]["BASE"]["CURRENCY"]) ?> text-nowrap">
                                <? if (Account::isAuthorized() && !empty($arItem["PRICES"]["BASE"]["DISCOUNT_DIFF_PERCENT"])): ?>
                                    <?= \Hogart\Lk\Helper\Template\Money::show($arItem["PRICES"]["BASE"]["DISCOUNT_VALUE"]) ?>
                                <? else: ?>
                                    <?= \Hogart\Lk\Helper\Template\Money::show($arItem["PRICES"]["BASE"]["VALUE"]) ?>
                                <? endif; ?>
                                <i class="fa fa-<?=strtolower($arItem["PRICES"]["BASE"]["CURRENCY"])?>" aria-hidden="true"></i>
                            </div>
                            <!--Только для авторизованных-->
                            <? if (Account::isAuthorized() && !empty($arItem["PRICES"]["BASE"]["DISCOUNT_DIFF_PERCENT"])): ?>
                                <div class="grid-hide discount">
                                    <?= $arItem["PRICES"]["BASE"]["DISCOUNT_DIFF_PERCENT"] ?>%
                                </div>
                            <? endif; ?>
                            <!---->
                        </div>
                        <div class="col-md-6 col-sm-12 text-right text-nowrap">
                            <div class="quantity-wrapper">
                                <? if ($arItem["CATALOG_QUANTITY"] > 0): ?>
                                <div class="quantity quantity-success line <? if (Account::isAuthorized()): ?> line2<? endif; ?>">
                                <? if (Account::isAuthorized()): ?>
                                    <span><?= $arItem["CATALOG_QUANTITY"]; ?>
                                    <?=$arItem['CATALOG_MEASURE_NAME']?>.</span>
                                <? else: ?>
                                    <i class="fa fa-check" aria-hidden="true"></i> Наличие
                                <? endif; ?>
                                </div>
                                <? else: ?>
                                <div class="quantity quantity-fail text-nowrap">
                                    <i class="fa fa-truck" aria-hidden="true"></i> Заказ
                                </div>
                                <? endif; ?>

                                <? if (Account::isAuthorized() && ($arItem["CATALOG_QUANTITY"] > 0 || !empty($arItem["PROPERTIES"]["days_till_receive"]["VALUE"]))): ?>
                                <div class="stocks-wrapper">
                                    <div class="triangle-with-shadow"></div>
                                    <div class="stock-header">
                                        <?= $arItem["NAME"]?>, <?= $arResult["ALL_BRANDS"][$arItem["PROPERTIES"]["brand"]["VALUE"]]['NAME'] ?> <?= $arItem["PROPERTIES"]["sku"]["VALUE"] ?>
                                    </div>
                                    <div class="stock-items">
                                        <div class="stock-items-table">
                                        <? foreach ($arResult['STORES'] as $store_id => $store): ?>
                                            <? if (!$arItem['STORE_AMOUNTS'][$store_id]['is_visible'] && empty($arItem["PROPERTIES"]["days_till_receive"]["VALUE"])) continue; ?>
                                            <div class="stock-item">
                                                <span class="stock-name h4 text-left">
                                                    <?= $store["TITLE"]?>
                                                </span>
                                                <span class="quantity">
                                                    <div>
                                                        <div class="amount h4">
                                                            <?= (int)$arItem['STORE_AMOUNTS'][$store_id]['stock'] ?> <?=$arItem['CATALOG_MEASURE_NAME']?>.
                                                        </div>
                                                        <div class="desc h6">
                                                            Остаток
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="amount h4">
                                                            <?= (int)$arItem['STORE_AMOUNTS'][$store_id]['in_reserve'] ?> <?=$arItem['CATALOG_MEASURE_NAME']?>.
                                                        </div>
                                                        <div class="desc h6">
                                                            Резерв
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="amount h4">
                                                            <?= (int)$arItem['STORE_AMOUNTS'][$store_id]['in_transit'] ?> <?=$arItem['CATALOG_MEASURE_NAME']?>.
                                                        </div>
                                                        <div class="desc h6">
                                                            Ожидается
                                                        </div>
                                                    </div>
                                                    <? if (!empty($arItem["PROPERTIES"]["days_till_receive"]["VALUE"])): ?>
                                                    <div>
                                                        <div class="amount h4">
                                                            <i class="glyphicon glyphicon-time"></i>
                                                            <?= (int)$arItem["PROPERTIES"]["days_till_receive"]["VALUE"] ?> дн.
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
                    <!--Только для авторизованных-->
                    <? if (Account::isAuthorized()): ?>
                    <div class="row <?= (!empty($arItem["PRICES"]["BASE"]["DISCOUNT_DIFF_PERCENT"]) ? "vertical-align" : "") ?>">
                        <? if (!empty($arItem["PRICES"]["BASE"]["DISCOUNT_DIFF_PERCENT"])): ?>
                        <div class="col-sm-8">
                            <div class="info-block text-nowrap">
                                <div class="old currency">
                                    <?= HogartHelpers::woPrice($arItem["PRICES"]["BASE"]["PRINT_VALUE"]); ?>
                                    <i class="fa fa-<?=strtolower($arItem["PRICES"]["BASE"]["CURRENCY"])?>" aria-hidden="true"></i>
                                </div>
                                <div class="discount">
                                    <?= $arItem["PRICES"]["BASE"]["DISCOUNT_DIFF_PERCENT"] ?>%
                                </div>
                            </div>
                        </div>
                        <? endif; ?>
                        <div class="col-sm-12 col-md-4 catalog-list__button text-right pull-right">
                            <?= \Hogart\Lk\Helper\Template\Cart::Link(
                                '<span class="btn__cart">Добавить в корзину</span><i class="fa fa-cart-plus" aria-hidden="true"></i><span class="button-perechen">Купить</span>',
                                [
                                    'item_id' => $arItem['ID'],
                                    'count' => '1'
                                ],
                                'class="black buy button-cart"'
                            ) ?>
                        </div>
                    </div>
                    <? endif; ?>
                    <? if(!empty($arItem["PRICES"]["BASE"]["PRINT_VALUE"])): ?>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 catalog-list__button text-center">
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
                                [
                                    'item_id' => $arItem['ID'],
                                ],
                                'class="black grid-hide button-mobile ' . $class_pop . ' ' . $attr_pop . '"'
                            ) ?>
                        </div>
                    </div>
                    <? endif; ?>
                    <!---->
                </div>
            </div>
        </li>
        <? if ($arParams["IS_TABLE_VIEW"]) {$mobileHtml .= ob_get_clean();} ?>
    <? //endif; ?>

<? endforeach; ?>

<? if (!empty($collectionId) && $arParams["IS_TABLE_VIEW"]): ?>
    <!-- закрываем блок коллекции -->
    </ul></div>
    <?= $mobileHtml . '</ul></div>'; unset($mobileHtml); ?>
    <? $collectionId = null; $table_sort = null; ?>
<? endif; ?>

<? if (!empty($brandId) && $arParams["IS_TABLE_VIEW"]): ?>
    <? if ($brand_block): ?>
        </ul>
        <!-- конец блока товаров без коллекции -->
        <? $brand_block = false; ?>
    <? endif; ?>
    <!-- закрываем блок бренда -->
    </ul></li>
    <?  $brandId = null; ?>
<? endif; ?>

<? if(!empty($arResult["NAV_STRING"])): ?>
    <div class="text-center">
        <?= $arResult["NAV_STRING"]; ?>
    </div>
<? endif; ?>

<? if(!empty($arResult["DESCRIPTION"])): ?>
    <div class="ceo-text">
        <?= $arResult["DESCRIPTION"] ?>
    </div>
<? endif; ?>

<? if (!empty($arResult["SUBS"])): ?>
    <? $this->SetViewTarget('CATALOG_SUBS'); ?>
    <div class="catalog-subs">
    <? foreach ($arResult["SUBS"] as $sub): ?>
        <div class="row">
            <div class="col-md-12">
                <a data-src-href="<?= $sub["SECTION_PAGE_URL"] ?>" href="javascript:void(0)" onclick="smartFilter.sectionFilter(this);"><?= $sub["NAME"] ?></a>
            </div>
        </div>
    <? endforeach; ?>
    </div>
    <? $this->EndViewTarget(); ?>
<? endif; ?>
