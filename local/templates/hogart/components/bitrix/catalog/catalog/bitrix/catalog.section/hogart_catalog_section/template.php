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
<h1><?= $arResult["NAME"] ?></h1>
<!--Если пользователь не авторизован-->

<small class="green-bg">
    В каталоге представлены рекомендуемые розничные цены
</small>
<? if ($arResult['DEPTH_LEVEL'] > '1') : ?>
<div class="smart-filter-wrapper">
    <? CStorage::getVar("SECTION_FILTER_HTML"); ?>
</div>
<? endif; ?>
<!---->
<? if (!$arParams["IS_TABLE_VIEW"]): ?>
<div class="view-filter">
    <div class="left">
        <span>Выводить:</span>
        <?foreach ($arParams['VIEW_TYPES'] as $type => $name) {
            $active = $type == $arParams['VIEW_TYPE'] ? "active":"";
            echo "<a class=\"icon-".$type." ".$active." js-trigger-perechen\" href=\"#".$type."\">".$name."</a>";
        }?>
    </div>
    <div class="right">
        <span>Сортировать по:</span>
        <a
            href="<?= $APPLICATION->GetCurPageParam("sort=shows&order=desc", array("sort", "order")); ?>"
            <? if ($_REQUEST['sort'] == 'shows' || !isset($_REQUEST['sort'])): ?>class="active"<? endif; ?>
            >
            Популярности
        </a>

        <a
            href="<?= $APPLICATION->GetCurPageParam("sort=catalog_PRICE_1&order=asc", array("sort", "order")); ?>"
            <? if ($_REQUEST['sort'] == 'catalog_PRICE_1'): ?>class="active"<? endif; ?>
            >
            Цене
        </a>

        <a
            href="<?= $APPLICATION->GetCurPageParam("sort=created_date&order=desc", array("sort", "order")); ?>"
            <? if ($_REQUEST['sort'] == 'created_date'): ?>class="active"<? endif; ?>
            >
            Новизне
        </a>
    </div>
</div>
<? endif; ?>
<ul class="perechen-produts js-target-perechen <?=$arParams['VIEW_TYPE']?> <? if ($arParams["IS_TABLE_VIEW"]): ?>table-view<? endif; ?>">
<? $collectionId = null; $brandId = null; ?>
<? foreach ($arResult["ITEMS"] as $arItem):?>
        <?
        $rsFile = CFile::GetByID($arItem['PROPERTIES']['photos']['VALUE'][0]);
        $arFile = $rsFile->Fetch();
        ?>

    <? if ($arParams["IS_TABLE_VIEW"]): ?>
        <? if (!empty($collectionId) && $collectionId != $arItem["PROPERTIES"]["collection"]["VALUE"]): ?>
            </ul></div>
            <!-- закрываем блок коллекции <?= $collectionId ?> -->
            <?  $collectionId = ""; ?>
        <? endif; ?>
        <? if (!empty($brandId) && $brandId != $arItem["PROPERTIES"]["brand"]["VALUE"]): ?>

            <? if ($brand_block): ?>
                </ul>
                <!-- конец блока товаров без коллекции -->
                <? $brand_block = false; ?>
            <? endif; ?>

            </div></li>
            <!-- закрываем блок бренда <?= $brandId ?> -->
        <? endif; ?>

        <? ob_start(); ?>
        <li class="brand-collection-header">
            <span class="cell"><?= $arItem["PROPERTIES"]["sku"]["NAME"] ?></span>
            <span class="cell">Наименование</span>
            <? foreach ($arItem["PROPERTIES"] as $propertyName => $arProperty): ?>
                <? if ($arProperty["DISPLAY_EXPANDED"] == "Y"): ?>
                    <span class="cell"><?= $arProperty["NAME"]; ?></span>
                <? endif; ?>
            <? endforeach; ?>
            <span class="cell">Наличие</span>
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
                        <div class="brand-name"><?= $arResult["ALL_BRANDS"][$brandId]["NAME"] ?></div>
                        <hr class="brand-hr">
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
                        $file = CFile::ResizeImageGet(
                            $collection["DETAIL_PICTURE"], array("width" => 400, "height" => 160), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                        ?>
                        <img src="<?= $file['src'] ?>" alt="<?= $collection["NAME"] ?>">
                    </div>
                <? endif; ?>
                <div class="collection-description">
                    <div class="collection-title"><?= $collection["NAME"] ?></div>
                    <div class="collection-text"><?= $collection["DETAIL_TEXT"] ?></div>
                </div>
            </li>
            <?= $brand_collection_header ?>
        <? endif; ?>

        <li <? if (!empty($collectionId)):?> data-collection-item-id="<?= $arItem["ID"] ?>"<? endif; ?> <? if (!empty($brandId) && empty($collectionId)):?> data-brand-item-id="<?= $arItem["ID"] ?>"<? endif; ?> >
            <span class="cell"><a href="<?= $arItem["DETAIL_PAGE_URL"] ?>"><?= $arItem["PROPERTIES"]["sku"]["VALUE"] ?></a></span>
            <span class="cell"><a href="<?= $arItem["DETAIL_PAGE_URL"] ?>"><?= $arItem["NAME"] ?></a></span>
            <? foreach ($arItem["PROPERTIES"] as $propertyName => $arProperty): ?>
                <? if ($arProperty["DISPLAY_EXPANDED"] == "Y"): ?>
                    <span class="cell"><?= $arProperty["VALUE"]; ?></span>
                <? endif; ?>
            <? endforeach; ?>
            <span class="cell quantity text-center <? if ($arItem["CATALOG_QUANTITY"] > 0): ?>quantity--available<? endif; ?>">
                <div class="<? if ($USER->IsAuthorized()):?>quantity-wrapper<? endif; ?>">
                <? if ($arItem["CATALOG_QUANTITY"] > 0): ?>
                    <? if (!$USER->IsAuthorized()): ?>
                        <i class="fa fa-check" aria-hidden="true"></i>
                    <? endif; ?>
                <? else: ?>
                    <i class="fa fa-close" aria-hidden="true"></i>
                    <span style="white-space: nowrap">Под заказ</span>
                <? endif; ?>

                <? if ($USER->IsAuthorized() && $arItem["CATALOG_QUANTITY"] > 0): ?>
                    <span style="white-space: nowrap"><?= $arItem["CATALOG_QUANTITY"]; ?> <?=$arItem['CATALOG_MEASURE_NAME']?>.</span>
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
            <span class="cell text-center buy-quantity">
                <i class="fa fa-minus" onclick="Math.max(0, this.nextElementSibling.value--)"></i>
                <input type="text" name="quantity" value="0" />
                <i class="fa fa-plus" onclick="Math.min(99, this.previousElementSibling.value++)"></i>
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
        <li>
            <div>
                <span class="perechen-img">
                    <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>">
                        <?
                        $pic = "/images/project_no_img.jpg";
                        if (!empty($arItem["PREVIEW_PICTURE"]['SRC'])) {
                            $file = CFile::ResizeImageGet(
                                $arItem["PREVIEW_PICTURE"]['ID'], array("width" => 400, "height" => 160), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                            $pic = $file['src'];
                        }
                        elseif (!empty($arItem["DETAIL_PICTURE"]['SRC'])) {

                            $file = CFile::ResizeImageGet(
                                $arItem["DETAIL_PICTURE"]['ID'], array("width" => 400, "height" => 160), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                            $pic = $file['src'];
                        }?>
                        <img src="<?=$pic?>" alt=""/>
                    </a>
                </span>

                <div class="prod-box">
                    <? if (!empty($arItem["PROPERTIES"]["sku"]["VALUE"])): ?>
                        <div class="art">Артикул: <span><?= $arItem["PROPERTIES"]["sku"]["VALUE"] ?></span></div>
                    <? endif; ?>
                    <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>"><h3><?= $arItem["NAME"] ?></h3></a>
                </div>
                <div>
                    <div class="col3 param-cnt">
                        <ul class="param">
                            <?
                            $propertyName = 'brand';
                            $arProperty = $arItem['PROPERTIES'][$propertyName]
                            ?>
                            <li class="note">
                                <span><?= $arProperty["NAME"] ?></span>
                                <a href="<?= $arResult["ALL_BRANDS"][$arProperty["VALUE"]]['DETAIL_PAGE_URL'] ?>">
                                    <span class="pr"><?= $arResult["ALL_BRANDS"][$arProperty["VALUE"]]['NAME'] ?></span>
                                </a>
                            </li>
                            <li class="note">
                                <span><?= $arItem['DISPLAY_PROPERTIES']["collection"]['NAME'] ?></span>
                                <?$collectionElement = current($arItem['DISPLAY_PROPERTIES']["collection"]["LINK_ELEMENT_VALUE"]);?>
                                <span class="pr"><?= $collectionElement['NAME'] ?></span>
                            </li>
                            <? unset($arItem['PROPERTIES'][$propertyName]) ?>
                            <? unset($arItem['PROPERTIES']['collection']) ?>
                            <?$hiddenPropsExist=false;?>
                            <? foreach ($arItem["PROPERTIES"] as $propertyName => $arProperty): ?>
                                <? if (!empty($arProperty["VALUE"]) && $arProperty['SMART_FILTER'] == 'Y'): ?>
                                    <? if (substr($propertyName, 0, 4) == "coll"): ?>
                                        <li class="note">
                                            <span><?= $arProperty["NAME"] ?></span>
                                            <span class="pr"><?= $arResult["ALL_COLLS"][$arProperty["VALUE"]]['NAME'] ?></span>
                                        </li>
                                    <? elseif (substr($propertyName, 0, 3) != "pho"): ?>
                                        <li class="note"
                                            <? if ($arProperty['DISPLAY_EXPANDED'] != 'Y') { $hiddenPropsExist=true;?>style="display: none"<? } ?>>
                                            <span><?= $arProperty["NAME"] ?></span>
                                            <span class="pr"><?= $arProperty["VALUE"] ?></span>
                                        </li>
                                    <? endif; ?>
                                <? endif; ?>
                            <? endforeach; ?>
                            <?if ($hiddenPropsExist) {?>
                                <li class="open">развернуть</li>
                            <?}?>
                        </ul>
                    </div>
                    <div class="col3 price-cnt <? if ($USER->IsAuthorized()): ?> auth-block<? endif; ?>">
                        <div class="row">
                            <div class="col2">
                                <div class="price currency-<?= strtolower($arItem["PRICES"]["BASE"]["CURRENCY"]) ?>">
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
                            <div class="col2 text-right">
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
                                   class="empty-btn black grid-hide <?= $class_pop ?>" <?= $attr_pop ?>
                                   href="javascript:void(0)" rel="nofollow">
                                    <i class="icon-cart"></i> Купить
                                </a>
                            </div>
                        </div>
                        <!--Только для авторизованных-->
                        <? if ($USER->IsAuthorized() && !empty($arItem["PRICES"]["BASE"]["DISCOUNT_DIFF_PERCENT"])): ?>
                            <div class="info-block grid-hide">
                                <div class="old currency-<?= strtolower($arItem["PRICES"]["BASE"]["CURRENCY"]) ?>"><?= HogartHelpers::wPrice($arItem["PRICES"]["BASE"]["PRINT_VALUE"]); ?></div>
                                <p>Цена указана с учетом скидки клиента</p>
                            </div>
                        <? endif; ?>
                        <!---->
                        <hr class="grid-hide"/>
                        <div class="icon-carTon">
                            <? if ($arItem["CATALOG_QUANTITY"] > 0): ?>
                                <div class="line <? if ($USER->IsAuthorized()): ?> line2<? endif; ?>">В
                                    наличии<? if ($USER->IsAuthorized()): ?> <span><?= $arItem["CATALOG_QUANTITY"]; ?>
                                        <?=$arItem['CATALOG_MEASURE_NAME']?>.</span><? endif; ?></div>
                            <? else: ?>
                                Под заказ
                                <? if (!empty($arItem["PROPERTIES"]["delivery_period"]["VALUE"])): ?>
                                    <br>
                                    Срок поставки
                                    <span><?= $arItem["PROPERTIES"]["delivery_period"]["VALUE"] ?> <?= number($arItem["PROPERTIES"]["delivery_period"]["VALUE"], array('день', 'дня', 'дней'))
                                        ?></span>
                                <? endif; ?>
                            <? endif; ?>
                        </div>
                    </div>
                </div>
                <!--                    </div>-->
            </div>
        </li>
    <? endif; ?>

<? endforeach; ?>

<? if (!empty($collectionId) && $arParams["IS_TABLE_VIEW"]): ?>
    <!-- закрываем блок коллекции -->
    </ul></div>
    <?  $collectionId = null; ?>
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

</div>
<div class="text-center">
<? echo $arResult["NAV_STRING"]; ?>
</div>
<div class="ceo-text">
<?= $arResult["DESCRIPTION"] ?>
</div>
</div>

<? $this->SetViewTarget('top_section_wrapper') ?>

<? if ($arResult['DEPTH_LEVEL'] <= 1): ?>
    <aside class="sidebar category js-fh js-fixed-block js-paralax-height" data-fixed="top">
    <div class="inner js-paralax-item">
    <div class="side_href">

        <a href="/documentation/" <? /*href="/documentation/?direction[]=<?= $arResult['ID'] ?>&direction_<?= $arResult['ID'] ?>_left=<?= $arResult['LEFT_MARGIN'] ?>&direction_<?= $arResult['ID'] ?>_right=<?= $arResult['RIGHT_MARGIN'] ?>"*/ ?>
           class="icon_doc">Перейти<br>к документации</a>

        <? if ($arResult["eqSelectID"]) { ?>
            <a href="/selection-equipment/#tab<?= $arResult["eqSelectID"] ?>" class="icon_ok">Заявка<br>на подбор<br>оборудования</a>
        <? } ?>
        <? if ((int)$arResult["UF_PRICE"] > 0) { ?>
            <a href="<?= CFile::GetPath($arResult["UF_PRICE"]); ?>" class="doc_view icon_doc" download>Скачать
                каталог</a>
        <? } ?>

    </div>
<? endif; ?>
<? $this->EndViewTarget() ?>