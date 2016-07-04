<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
if (!isset($arParams['ITEM_TEMPLATE']) && empty($arParams['ITEM_TEMPLATE'])):?>
    <div class="row vertical-align">
        <div class="col-md-10">
            <h3 style="margin-top: 10px"><?= $arResult['NAME'] ?></h3>
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
    
    <? if (count($arResult['PROPERTIES']['prop_proj']) > 0): ?>
        <div class="con-box">
            <? foreach ($arResult['PROPERTIES']['prop_proj_int']['VALUE'] as $key => $arItem): ?>
                <div class="box-item">
                    <div>
                        <div class="padding-text">
                            <?= $arItem; ?>
                        </div>
                    </div>
                    <div><?= $arResult['PROPERTIES']['prop_proj']['VALUE'][$key]; ?></div>
                </div>
            <? endforeach; ?>
        </div>
    <? endif; ?>
    <? if (!empty($arResult["DETAIL_TEXT"])): ?>
        <?= $arResult["DETAIL_TEXT"] ?>
    <? endif; ?>
    <? if (!empty($arResult["DETAIL_PICTURE"])): ?>
        <div class="text-center inner no-padding">
            <img src="<?= $arResult['DETAIL_PICTURE']['SRC'] ?>" class="paddingimg" alt="<?= $arResult['NAME'] ?>"/>
        </div>
    <? endif; ?>
    <? if (!empty($arResult["PROPERTIES"]['problem'])): ?>
        <div class="inner no-full">
            <h3><?= GetMessage("Особенности проекта") ?></h3>
            <?= $arResult["PROPERTIES"]['problem']['~VALUE']['TEXT']; ?>
            <?= $arResult["PROPERTIES"]['solution_project']['~VALUE']['TEXT']; ?>
        </div>
    <? endif; ?>

    <? if (isset($arResult["this_goods"])): ?>
        <div class="catalog_page">
            <div class="products-similar-tabs">
                <h3>Решение для хостелов</h3>
                <div class="items-similar">
                    <div id="tab-1" class="item-similar active" style="display: block;">
                        <? if (count($arResult["this_goods"]) > 3): ?>
                            <div id="con-4" class="controls text-right">
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
                        <? endif; ?>
                        <ul data-control="#con-4" class="js-slider-similar">
                            <? foreach ($arResult["this_goods"] as $arProduct): ?>
                                <li>
                                    <span class="preview-img">
                                        <? if (!empty($arProduct["PREVIEW_PICTURE"])) {
                                            $file = CFile::ResizeImageGet($arProduct["PREVIEW_PICTURE"],
                                                array('width' => 406, 'height' => 142), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
                                            $file = $file['src'];
                                        } elseif (!empty($arProduct["PROPERTY_PHOTOS_VALUE"])) {
                                            $file = CFile::ResizeImageGet($arProduct["PROPERTY_PHOTOS_VALUE"],
                                                array('width' => 406, 'height' => 142), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
                                            $file = $file['src'];
                                        } else {
                                            $file = '/images/project_no_img.jpg';
                                        } ?>
                                        <a href="<?= $arProduct["DETAIL_PAGE_URL"] ?>" style="background-image: url(<?= $file ?>)"></a>
                                    </span>
                                    <a href="<?= $arProduct["DETAIL_PAGE_URL"] ?>"><h3><?= $arProduct["NAME"] ?></h3>
                                    </a>
                                    <? if (!empty($arProduct["PROPERTY_SKU_VALUE"])): ?>
                                        <span
                                            class="art">Артикул: <span><?= $arProduct["PROPERTY_SKU_VALUE"] ?></span></span>
                                    <? endif; ?>
                                    <div class="param">
                                        <div>
                                            <dl>
                                                <dt>Бренд</dt>
                                                <dd class="pr"><?= $arProduct['BRAND_NAME'] ?></dd>
                                            </dl>
                                            <? if (strlen($arProduct['COLLECTION_NAME']) > 0) { ?>
                                                <dl>
                                                    <dt>Коллекция</dt>
                                                    <dd class="pr"><?= $arProduct['COLLECTION_NAME'] ?></dd>
                                                </dl>
                                            <? } ?>
                                        </div>
                                        <?= HogartHelpers::getAdjacentProductPropertyHtml($arProduct['ID'], $arProduct["SHOW_PROPS"], $arProduct["HIDDEN_PROPS"], array('brand',
                                            'photos',
                                            'sku',
                                            'collection')); ?>
                                    </div>
                                    <div class="price currency-<?= strtolower($arProduct['CATALOG_CURRENCY_1']) ?>">
                                        <?= HogartHelpers::wPrice($arProduct['PRICE']) ?>
                                    </div>
                                </li>
                            <? endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    <? endif; ?>

    <? if (!empty($arResult["PROPERTIES"]['review_text']['VALUE'])): ?>
        <div class="inner no-full">
            <h3>Отзыв клиента</h3>
            <ul class="complex-comments-list">

                <li>
                    <div class="inner">
                        <div class="photo">
                            <img src="<?= CFile::GetPath($arResult["PROPERTIES"]['review_photo']['VALUE']); ?>" alt=""/>
                        </div>
                        <div class="text">
                            <p>
                                <?= $arResult["PROPERTIES"]['review_text']['~VALUE']['TEXT']; ?>
                            </p>

                            <div class="name"><?= $arResult["PROPERTIES"]['review_author']['VALUE']; ?></div>
                            <div class="sign"><?= $arResult["PROPERTIES"]['review_author_status']['VALUE']; ?></div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    <? endif; ?>
    <? if (!empty($arResult['PROPERTIES']['photogallery']['VALUE'])): ?>
        <? if (count($arResult['PROPERTIES']['photogallery']['VALUE']) <= 4): ?>
            <? if (!empty($arResult['PROPERTIES']['photogallery']['VALUE'])): ?>
                <div class="gall-news-one">
                    <? foreach ($arResult['PROPERTIES']['photogallery']['VALUE'] as $photo):
                        $photoBig = CFile::GetPath($photo);
                        $photo = CFile::ResizeImageGet(
                            $photo,
                            array("width" => 320, "height" => 320),
                            BX_RESIZE_IMAGE_EXACT,
                            true
                        );
                        ?>
                        <div class="img-wrap">
                            <img class="js-popup-open-img" title="<?= $arResult["NAME"] ?>" data-group="gallG"
                                 src="<?= $photoBig ?>" alt=""/>
                        </div>
                    <? endforeach; ?>
                </div>
            <? endif ?>
        <? else: ?>
            <div class="row gallery-slider" id="galElWidth">
                <div class="controls text-right">
                    <div class="prev" id="galP">
                        <i class="fa fa-arrow-circle-o-left"></i>
                    </div>
                    <div class="next" id="galN">
                            <i class="fa fa-arrow-circle-o-right"></i>
                    </div>
                </div>
                <ul class="js-normal-slider-init" style="min-width: 500px" data-width="#galElWidth" data-next="#galN"
                    data-prev="#galP">
                    <? $countGalImg = 0; ?>
                    <li>
                        <div class="gall-news-one">
                            <? foreach ($arResult['PROPERTIES']['photogallery']['VALUE'] as $photo):
                                $photoBig = CFile::GetPath($photo);
                                $photo = CFile::ResizeImageGet(
                                    $photo,
                                    array("width" => 320, "height" => 320),
                                    BX_RESIZE_IMAGE_EXACT,
                                    true
                                );
                                ?>
                                <div class="img-wrap">
                                    <img class="js-popup-open-img" data-group="gallG" src="<?= $photo['src'] ?>"
                                         alt=""/>
                                </div>
                                <? $countGalImg++;
                                if ($countGalImg % 4 == 0) {
                                    $countGalImg = 0;
                                    echo '</div></li>';
                                    if ($countGalImg != count($arResult['PROPERTIES']['photogallery']['VALUE'])) {
                                        echo '<li><div class="gall-news-one">';
                                    }
                                }
                                ?>
                            <? endforeach; ?>
                        </div>
                    </li>
                </ul>
            </div>
        <? endif ?>
    <? endif ?>
    <? if (!empty($arResult['PROPERTIES']['video']['VALUE'])) : ?>
        <div class="proj-video-box">
            <div class="open-video">Видео презентация</div>
            <div id="proj-video" class="popup-img-cnt " style="display: none;">
                <div class="inner-popup-img">
                    <div class="content-img">
                        <div class="proj-video-player">
                            <? $APPLICATION->IncludeComponent("bitrix:player",
                                "proj-player",
                                Array(
                                    "PLAYER_TYPE" => "auto",
                                    "USE_PLAYLIST" => "N",
                                    "PATH" => CFile::GetPath($arResult['PROPERTIES']['video']['VALUE']),
                                    "PROVIDER" => "video",
                                    "STREAMER" => "",
                                    "WIDTH" => "600",
                                    "HEIGHT" => "400",
                                    "PREVIEW" => "",
                                    "FILE_TITLE" => "Вступление",
                                    "FILE_DURATION" => "305",
                                    "FILE_AUTHOR" => "Иван Иванов",
                                    "FILE_DATE" => "01.08.2010",
                                    "FILE_DESCRIPTION" => "Презентация продукта",
                                    "SKIN_PATH" => "/bitrix/components/bitrix/player/mediaplayer/skins",
                                    "SKIN" => "bitrix.swf",
                                    "CONTROLBAR" => "bottom",
                                    "WMODE" => "windowless",
                                    "ADDITIONAL_FLASHVARS" => "",
                                    "SHOW_CONTROLS" => "Y",
                                    "SHOW_DIGITS" => "Y",
                                    "CONTROLS_BGCOLOR" => "FFFFFF",
                                    "CONTROLS_COLOR" => "000000",
                                    "CONTROLS_OVER_COLOR" => "000000",
                                    "SCREEN_COLOR" => "000000",
                                    "AUTOSTART" => "N",
                                    "REPEAT" => "list",
                                    "VOLUME" => "90",
                                    "MUTE" => "N",
                                    "HIGH_QUALITY" => "Y",
                                    "SHUFFLE" => "N",
                                    "START_ITEM" => "1",
                                    "ADVANCED_MODE_SETTINGS" => "Y",
                                    "PLAYER_ID" => "",
                                    "BUFFER_LENGTH" => "10",
                                    "ADDITIONAL_WMVVARS" => "",
                                    "ALLOW_SWF" => "Y",
                                )
                            ); ?>
                        </div>
                    </div>
                    <div class="close"></div>
                </div>
            </div>
        </div>

    <? endif; ?>
    <div class="inner print-other-project">
        <h3><?= GetMessage("Другие проекты") ?></h3>
        <?
        $APPLICATION->IncludeComponent("kontora:element.list", "else-projects", array(
            'IBLOCK_ID' => '18',
            'PROPS' => 'Y',
            "SEF_MODE" => "Y",
            'FILTER' => array("!ID" => $arResult['ID'], "SECTION_ID" => $arResult['ACTIVE_SECTION_ID']),
            "SEF_FOLDER" => "/integrated-solutions/",
        ));
        ?>
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
    <?
else:
    echo htmlspecialchars_decode(str_replace(explode(",", "^" . implode("^,^", array_keys($arResult)) . "^"),
        array_values($arResult), $arParams['ITEM_TEMPLATE']));
endif; ?>
