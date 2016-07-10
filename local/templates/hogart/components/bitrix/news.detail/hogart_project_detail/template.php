<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
if (!isset($arParams['ITEM_TEMPLATE']) && empty($arParams['ITEM_TEMPLATE'])):?>
<div class="row">
    <div class="col-md-<?= (!empty($arResult['PROPERTIES']['infographics']['VALUE']) ? "9" : "12") ?>">

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
        <? if (!empty($arResult["DETAIL_TEXT"])): ?>
            <!-- Адрес -->
            <div class="row">
                <div class="col-md-12">
                    <span class="address"><?= $arResult["DETAIL_TEXT"] ?></span>
                </div>
            </div>
        <? endif; ?>
        <div class="row">
            <div class="col-md-5">
                <? if (!empty($arResult["DETAIL_PICTURE"])): ?>
                    <div class="text-center inner no-padding">
                        <img style="padding-top: 10px;" src="<?= $arResult['DETAIL_PICTURE']['SRC'] ?>" class="paddingimg" alt="<?= $arResult['NAME'] ?>"/>
                    </div>
                <? endif; ?>
            </div>
            <div class="col-md-7">
                <? if (!empty($arResult["PROPERTIES"]['description'])): ?>
                    <h3><?= GetMessage("Описание") ?></h3>
                    <?= $arResult["PROPERTIES"]['description']['~VALUE']['TEXT']; ?>
                <? endif; ?>
                <? if (!empty($arResult['PROPERTIES']['photogallery']['VALUE'])): ?>
                    <div style="display: flex; flex-direction: row; align-items: center;">
                        <h3><?= GetMessage("Фотографии объекта") ?></h3>
                        <? if (count($arResult['PROPERTIES']['photogallery']['VALUE']) > 2): ?>
                            <div class="controls text-right">
                                <div class="prev" id="galP">
                                    <i class="fa fa-arrow-circle-o-left"></i>
                                </div>
                                <div class="next" id="galN">
                                    <i class="fa fa-arrow-circle-o-right"></i>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        <? endif; ?>
                    </div>
                    <div class="row gallery-slider" id="galElWidth">
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
                                            BX_RESIZE_IMAGE_PROPORTIONAL,
                                            true
                                        );
                                        ?>
                                        <div class="img-wrap">
                                            <img class="js-popup-open-img" data-big-img="<?= $photoBig ?>" data-group="gallG" data-bi src="<?= $photo['src'] ?>"
                                                 alt=""/>
                                        </div>
                                        <? $countGalImg++;
                                        if ($countGalImg % 2 == 0) {
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
            </div>
        </div>

        <? if (!empty($arResult['PROPERTIES']['video']['VALUE'])) : ?>
            <div class="row">
                <div class="col-md-12">
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
                </div>
            </div>
        <? endif; ?>
        <? if (!empty($arResult["PROPERTIES"]['review_text']['VALUE'])): ?>
            <div class="row">
                <div class="col-md-12">
                    <h3>Отзыв</h3>
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
            </div>
        <? endif; ?>
        <div class="row">
            <div class="col-md-12">
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
        </div>
    </div>
    <? if (!empty($arResult['PROPERTIES']['infographics']['VALUE'])): ?>
    <div class="col-md-3 aside">
        <h3><?= GetMessage("Оборудование") ?></h3>
        <? foreach ($arResult['PROPERTIES']['infographics']['VALUE'] as $key => $infographic): ?>
            <div class="row vertical-align">
                <div class="col-md-3">
                    <?
                    $img = CFile::GetPath($infographic)
                    ?>
                    <img src="<?= $img ?>" alt="<?= $arResult['PROPERTIES']['infographics']['DESCRIPTION'][$key] ?>" />
                </div>
                <div class="col-md-9">
                    <?= $arResult['PROPERTIES']['infographics']['DESCRIPTION'][$key] ?>
                </div>
            </div>
        <? endforeach; ?>
    </div>
    <? endif; ?>
</div>
<? else:
    echo htmlspecialchars_decode(str_replace(explode(",", "^" . implode("^,^", array_keys($arResult)) . "^"),
        array_values($arResult), $arParams['ITEM_TEMPLATE']));
endif; ?>
