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

$viewTypes = array('list' => 'Списком', 'grid' => 'Сеткой');
$isTableViewExt = $arParams['VIEW_TYPE'] == 3;

if (false === $arParams['VIEW_TYPE']) {
    $arParams['VIEW_TYPE'] = $viewTypes[max(0, ($arResult["UF_SECTION_VIEW"] % 2) - 1)];
}

?>
<h1><?= $arResult["NAME"] ?></h1>
<!--Если пользователь не авторизован-->

<small class="green-bg">
    В каталоге представлены рекомендуемые розничные цены
</small>
<!---->
<div class="view-filter">
    <div class="left">
        <span>Выводить:</span>
        <?foreach ($viewTypes as $type => $name) {
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
<ul class="perechen-produts js-target-perechen <?=$arParams['VIEW_TYPE']?>">
    <? foreach ($arResult["ITEMS"] as $arItem):?>
        <?
        $rsFile = CFile::GetByID($arItem['PROPERTIES']['photos']['VALUE'][0]);
        $arFile = $rsFile->Fetch();
        ?>
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
                            <a id="<? echo $arItem['BUY_LINK']; ?>"
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
<? endforeach; ?>
</ul>
<div class="text-center">
<? echo $arResult["NAV_STRING"]; ?>
</div>
<div class="ceo-text">
<?= $arResult["DESCRIPTION"] ?>
</div>
</div>

<? $this->SetViewTarget('top_section_wrapper') ?>
<?fileDump($arResult, true);?>
<? if ($arResult['DEPTH_LEVEL'] <= 1) { ?>
    <aside class="sidebar category js-fh js-fixed-block js-paralax-height" data-fixed="top">
        <div class="inner js-paralax-item">
            <div class="side_href">

                <a href="/documentation/" <?/*href="/documentation/?direction[]=<?= $arResult['ID'] ?>&direction_<?= $arResult['ID'] ?>_left=<?= $arResult['LEFT_MARGIN'] ?>&direction_<?= $arResult['ID'] ?>_right=<?= $arResult['RIGHT_MARGIN'] ?>"*/?>
                   class="icon_doc">Перейти<br>к документации</a>

                <? if ($arResult["eqSelectID"]) { ?>
                                <a href="/selection-equipment/#tab<?= $arResult["eqSelectID"] ?>" class="icon_ok">Заявка<br>на подбор<br>оборудования</a>
                            <? } ?>
                                <?if ((int)$arResult["UF_PRICE"]>0) {?>
                            <a href="<?=CFile::GetPath($arResult["UF_PRICE"]); ?>" class="doc_view icon_doc" download>Скачать каталог</a>
                                <?}?>

            </div>
<? } else { ?>
            <aside class="sidebar js-fh js-fixed-block js-paralax-height perechen" data-fixed="top">
                <div class="inner js-paralax-item">
                    <div class="side_href">

    <? if ($arResult['DEPTH_LEVEL'] == 2) {

        ?>
                            <a href="/documentation/" <?/* href="/documentation/?direction[]=<?= $arResult['IBLOCK_SECTION_ID'] ?>&section_<?= $arResult['IBLOCK_SECTION_ID'] ?>=<?= $arResult['ID'] ?>&section_<?= $arResult['ID'] ?>_left=<?= $arResult['LEFT_MARGIN'] ?>&section_<?= $arResult['ID'] ?>_right=<?= $arResult['RIGHT_MARGIN'] ?>"*/?>
                               class="icon_doc">Перейти<br>к документации</a>

                            <? if ($arResult["eqSelectID"]) { ?>
                                <a href="/selection-equipment/#tab<?= $arResult["eqSelectID"] ?>" class="icon_ok">Заявка<br>на подбор<br>оборудования</a>
                            <? } ?>
                                   <?if ((int)$arResult["UF_PRICE"]>0) {?>
                            <a href="<?=CFile::GetPath($arResult["UF_PRICE"]); ?>" class="doc_view icon_doc" download>Скачать каталог</a>
                                <?}?>
                        <? } else { ?>
        <? $directionId = $arResult['PARENT_PARENT_SECTION_ID']; ?>
                            <a href="/documentation/" <?/*href="/documentation/?direction[]=<?= $directionId ?>&section_<?= $directionId ?>=<?= $arResult['ID'] ?>&section_<?= $arResult['ID'] ?>_left=<?= $arResult['LEFT_MARGIN'] ?>&section_<?= $arResult['ID'] ?>_right=<?= $arResult['RIGHT_MARGIN'] ?>"*/?>
                               class="icon_doc">Перейти<br>к документации</a>

                            <? if ($arResult["eqSelectID"]) { ?>
                                <a href="/selection-equipment/#tab<?= $arResult["eqSelectID"] ?>" class="icon_ok">Заявка<br>на подбор<br>оборудования</a>
                            <? } ?>
                                   <?if ((int)$arResult["UF_PRICE"]>0) {?>
                            <a href="<?=CFile::GetPath($arResult["UF_PRICE"]); ?>" class="doc_view icon_doc" download>Скачать каталог</a>
                                <?}?>
                    <? } ?>
                    </div>
                        <? if ($arResult['DEPTH_LEVEL'] == '2') : ?>
                        <div class="sidebar_padding_cnt">
        <? $page = $APPLICATION->GetCurDir(true); ?>

                            <!--            <div class="preview-project-viewport">-->
                            <!--                <div class="preview-project-viewport-inner">-->
                            <!--                    <br/><br/>-->
                            <!--                    <div class="head-links-box">-->
                            <!--                        <div class="head-links-wrapper">-->
                            <!--                            <ul class="head-links">-->
                            <div class="subs-links">
        <? foreach ($arResult['SUBS'] as $item): ?>
                                    <a <?= ($page == $item['SECTION_PAGE_URL']) ? 'class="active"' : '' ?>
                                        href="<?= $item['SECTION_PAGE_URL'] ?>"><?= $item['NAME'] ?></a>
        <? endforeach; ?>
                            </div>

                            <!--                            </ul>-->
                            <!--                        </div>-->
                            <!--                    </div>-->
                            <!--                </div>-->
                            <!--            </div>-->


                        </div>
                    <? endif; ?>
                <? } ?>
<? $this->EndViewTarget() ?>