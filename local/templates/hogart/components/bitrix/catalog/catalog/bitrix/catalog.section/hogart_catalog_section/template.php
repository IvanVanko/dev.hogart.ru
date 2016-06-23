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

?>
<ul class="row perechen-produts js-target-perechen <?=$arParams['VIEW_TYPE']?> <? if ($arParams["IS_TABLE_VIEW"]): ?>table-view<? endif; ?>">
<? $collectionId = null; $brandId = null; $table_sort = null; $sectionId = null; ?>
<? foreach ($arResult["ITEMS"] as $i => $arItem):?>
        <?
        $rsFile = CFile::GetByID($arItem['PROPERTIES']['photos']['VALUE'][0]);
        $arFile = $rsFile->Fetch();
        ?>

    <? if ($arParams["IS_TABLE_VIEW"]): ?>
        <? if (!empty($collectionId) && $collectionId != $arItem["PROPERTIES"]["collection"]["VALUE"]): ?>
            </ul></div>
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
            <? if ($USER->IsAuthorized()): ?>
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
                            <div class="preview"><?= $collection["PREVIEW_TEXT"] ?></div>
                        <? endif; ?>
                        <? if (!empty($collection["DETAIL_TEXT"])): ?>
                            <? if(!empty($collection["PREVIEW_TEXT"])): ?>
                            <div class="more">Далее</div>
                            <? endif; ?>
                            <div class="detail"><?= $collection["DETAIL_TEXT"] ?></div>
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
                <div class="<? if ($USER->IsAuthorized()):?>quantity-wrapper<? endif; ?>">
                <? if ($arItem["CATALOG_QUANTITY"] > 0): ?>
                    <? if (!$USER->IsAuthorized()): ?>
                        <i class="fa fa-check" aria-hidden="true"></i>
                    <? endif; ?>
                <? else: ?>
                    <span style="white-space: nowrap"><i class="fa fa-close" aria-hidden="true"></i> Под заказ</span>
                <? endif; ?>

                <? if ($USER->IsAuthorized() && $arItem["CATALOG_QUANTITY"] > 0): ?>
                    <span style="white-space: nowrap"><?= $arItem["CATALOG_QUANTITY"]; ?></span>
                    <div class="stocks-wrapper">
                        <div class="stock-header">
                            <?= $arItem["NAME"]?>, <?= $arResult["ALL_BRANDS"][$arItem["PROPERTIES"]["brand"]["VALUE"]]['NAME'] ?> <?= $arItem["PROPERTIES"]["sku"]["VALUE"] ?>
                        </div>
                        <div class="stock-items">
                            <div class="stock-items-table">
                            <? $store_keys = preg_grep("/^CATALOG_STORE_AMOUNT_/", array_keys($arItem)); ?>
                            <? foreach ($store_keys as $store_key): ?>
                                <?
                                if (!$arItem[$store_key]) continue;
                                $storeId = intval(str_replace("CATALOG_STORE_AMOUNT_", "", $store_key));
                                ?>
                                <div class="stock-item">
                                    <span class="stock-name text-left">
                                        <?= $arResult["STORES"][$storeId]["TITLE"]?>
                                        <div style="font-size: smaller"><?= $arResult["STORES"][$storeId]["ADDRESS"]?></div>
                                    </span>
                                    <span class="quantity"><?= $arItem[$store_key] ?> <?=$arItem['CATALOG_MEASURE_NAME']?>.</span>
                                </div>
                            <? endforeach; ?>
                            </div>
                        </div>
                        <i class="fa fa-caret-up" aria-hidden="true"></i>
                    </div>
                <? endif; ?>
                </div>
            </span>
            <span class="cell text-center"><?=$arItem['CATALOG_MEASURE_NAME']?>.</span>
            <span class="cell text-center price currency-<?= strtolower($arItem["PRICES"]["BASE"]["CURRENCY"]) ?>">
                <? if ($USER->IsAuthorized() && !empty($arItem["PRICES"]["BASE"]["DISCOUNT_DIFF_PERCENT"])): ?>
                    <?= HogartHelpers::woPrice($arItem["PRICES"]["BASE"]["PRINT_DISCOUNT_VALUE"]) ?>
                <? else: ?>
                    <?= HogartHelpers::woPrice($arItem["PRICES"]["BASE"]["PRINT_VALUE"]) ?>
                <? endif; ?>
            </span>
            <? if ($USER->IsAuthorized()): ?>
            <span class="cell text-center">
                <? if (!empty($arItem["PRICES"]["BASE"]["DISCOUNT_DIFF_PERCENT"])): ?>
                <div class="grid-hide discount">
                    <?= $arItem["PRICES"]["BASE"]["DISCOUNT_DIFF_PERCENT"] ?>%
                </div>
                <? endif; ?>
            </span>
            <span class="cell text-center buy-quantity noselect" style="white-space: nowrap">
                <i class="fa fa-minus" onclick="Math.max(0, this.nextElementSibling.value--)"></i>
                <input type="text" name="quantity" value="0" />
                <i class="fa fa-plus" onclick="this.previousElementSibling.value++"></i>
            </span>
            <? endif; ?>

            <span class="cell text-center buy">
                    <?
                    $class_pop = '';
                    $attr_pop = '';
                    if (!$USER->IsAuthorized()) {
                        $class_pop = 'js-popup-open';
                        $attr_pop = 'data-popup="#popup-msg-product"';
                    }
                    ?>
                <a id="<?= $arItem['BUY_URL'] ?>"
                   class="black grid-hide <?= $class_pop ?>" <?= $attr_pop ?>
                   href="javascript:void(0)" rel="nofollow">
                    <i class="fa fa-cart-plus" aria-hidden="true"></i>
                </a>
            </span>
        </li>

    <? else: ?>
        <? if($sectionId != $arItem["~IBLOCK_SECTION_ID"] && $arParams["DEPTH_LEVEL"] == 2 && !$arParams["IS_FILTERED"]): ?>
        <li class="col-md-12 section-title" data-section-id="<?= $arItem["~IBLOCK_SECTION_ID"] ?>" data-section-name="<?= $arResult["SUBS"][$arItem["~IBLOCK_SECTION_ID"]]["NAME"] ?>">
            <span>
                <?= $arResult["SUBS"][$arItem["~IBLOCK_SECTION_ID"]]["NAME"] ?>
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
        <ul class="row perechen-produts js-target-perechen <?=$arParams['VIEW_TYPE']?> <? if ($arParams["IS_TABLE_VIEW"]): ?>table-view<? endif; ?>">
        <? endif; ?>
        <? $brandId = $arItem["PROPERTIES"]["brand"]["VALUE"]; ?>
        
        <li class="col-md-12 caption" data-brand-id="<?= $arItem["PROPERTIES"]["brand"]["VALUE"] ?>" data-brand-name="<?= $arResult["ALL_BRANDS"][$brandId]["NAME"] ?>">
            <span class="brand-name"><i class="fa"></i><?= $arResult["ALL_BRANDS"][$brandId]["NAME"] ?></span>
        </li>
        <? endif; ?>
        
        <li class="col-lg-3 col-md-4 col-sm-6">
            <div>
                <span class="perechen-img">
                    <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>">
                        <?
                        $pic = "/images/project_no_img.jpg";
                        if (!empty($arItem["PREVIEW_PICTURE"]['SRC'])) {
                            $file = CFile::ResizeImageGet(
                                $arItem["PREVIEW_PICTURE"]['ID'], array("width" => 400, "height" => 160), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                            if (file_exists(realpath($_SERVER["DOCUMENT_ROOT"] . $file['src'])))
                                $pic = $file['src'];
                        }
                        elseif (!empty($arItem["DETAIL_PICTURE"]['SRC'])) {

                            $file = CFile::ResizeImageGet(
                                $arItem["DETAIL_PICTURE"]['ID'], array("width" => 400, "height" => 160), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                            if (file_exists(realpath($_SERVER["DOCUMENT_ROOT"] . $file['src'])))
                                $pic = $file['src'];
                        }
                        ?>
                        <img src="<?=$pic?>" alt=""/>
                    </a>
                </span>

                <div class="prod-box">
                    <? if (!empty($arItem["PROPERTIES"]["sku"]["VALUE"])): ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="art">Артикул: <span><?= $arItem["PROPERTIES"]["sku"]["VALUE"] ?></span></div>
                            </div>
                        </div>
                    <? endif; ?>
                    <div class="row">
                        <div class="col-md-12">
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
                <div class="price-cnt <? if ($USER->IsAuthorized()): ?> auth-block<? endif; ?>">
                    <div class="row vertical-align">
                        <div class="col-md-6">
                            <div class="price currency-<?= strtolower($arItem["PRICES"]["BASE"]["CURRENCY"]) ?> text-nowrap">
                                <? if ($USER->IsAuthorized() && !empty($arItem["PRICES"]["BASE"]["DISCOUNT_DIFF_PERCENT"])): ?>
                                    <?= HogartHelpers::wPrice($arItem["PRICES"]["BASE"]["PRINT_DISCOUNT_VALUE"]); ?>
                                <? else: ?>
                                    <?= HogartHelpers::wPrice($arItem["PRICES"]["BASE"]["PRINT_VALUE"]); ?>
                                <? endif; ?>
                            </div>
                            <!--Только для авторизованных-->
                            <? if ($USER->IsAuthorized() && !empty($arItem["PRICES"]["BASE"]["DISCOUNT_DIFF_PERCENT"])): ?>
                                <div class="grid-hide discount">
                                    <?= $arItem["PRICES"]["BASE"]["DISCOUNT_DIFF_PERCENT"] ?>%
                                </div>
                            <? endif; ?>
                            <!---->
                        </div>
                        <div class="col-md-6 text-right text-nowrap">
                        <? if ($arItem["CATALOG_QUANTITY"] > 0): ?>
                            <div class="quantity quantity-success line <? if ($USER->IsAuthorized()): ?> line2<? endif; ?>">В
                                наличии<? if ($USER->IsAuthorized()): ?> <span><?= $arItem["CATALOG_QUANTITY"]; ?>
                                    <?=$arItem['CATALOG_MEASURE_NAME']?>.</span><? endif; ?></div>
                        <? else: ?>
                            <div class="quantity quantity-fail text-nowrap">
                                <i class="fa fa-truck" aria-hidden="true"></i> Под заказ
                            </div>
                        <? endif; ?>
                        </div>
                    </div>
                    <!--Только для авторизованных-->
                    <? if ($USER->IsAuthorized() && !empty($arItem["PRICES"]["BASE"]["DISCOUNT_DIFF_PERCENT"])): ?>
                    <div class="info-block">
                        <div class="old currency-<?= strtolower($arItem["PRICES"]["BASE"]["CURRENCY"]) ?>"><?= HogartHelpers::wPrice($arItem["PRICES"]["BASE"]["PRINT_VALUE"]); ?></div>
                    </div>
                    <? endif; ?>
                    <? if(!empty($arItem["PRICES"]["BASE"]["PRINT_VALUE"])): ?>
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
                            <a id="<?= $arItem['BUY_URL'] ?>"
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
    <? endif; ?>

<? endforeach; ?>

<? if (!empty($collectionId) && $arParams["IS_TABLE_VIEW"]): ?>
    <!-- закрываем блок коллекции -->
    </ul></div>
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
