<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
global $seminarTitle;
$seminarTitle = $arResult['NAME'];
?>

<?php
    $date_end = strtotime(FormatDate("d.m.Y", MakeTimeStamp($arResult['PROPERTIES']['sem_end_date']['VALUE'])));
    $date_end = (!empty($date_end)) ? $date_end : 0;

    $now = strtotime(date($DB->DateFormatToPHP(CSite::GetDateFormat("SHORT")), time()));

    $semStartDate = FormatDate("d F", MakeTimeStamp($arResult['PROPERTIES']['sem_start_date']['VALUE']));
    $semStartDateFull = FormatDate("d F Y", MakeTimeStamp($arResult['PROPERTIES']['sem_start_date']['VALUE']));

    $semEndDate = FormatDate("d F Y", MakeTimeStamp($arResult['PROPERTIES']['sem_end_date']['VALUE']));
    $semStartTime = FormatDate("H:i", MakeTimeStamp($arResult['PROPERTIES']['sem_start_date']['VALUE']));
?>
<div class="row">
    <div class="col-md-9">
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

        <div class="img-title">
            <img src="<?= $arResult["DETAIL_PICTURE"]['SRC'] ?>" alt=""/>

            <? if (!empty($arResult['PROPERTIES']['sem_start_date']['VALUE'])): ?>
                <div class="date">
                    <? if (!empty($semStartDate) && FormatDate("Y", MakeTimeStamp($arResult['PROPERTIES']['sem_end_date']['VALUE'])) != "1970"): ?>
                        <sup>
                            <? if ($semStartDateFull != $semEndDate): ?>
                                с <?= $semStartDate ?> по <?= $semEndDate ?>
                            <? else: ?>
                                <?= $semEndDate ?>
                            <? endif; ?>
                        </sup>
                    <? else: ?>
                        <sup><?= $semStartDateFull; ?></sup>
                    <? endif; ?>
                    <? if (!empty($arResult['PROPERTIES']['time']['VALUE'])): ?>
                        <span>/</span>
                        <sub><?= $arResult['PROPERTIES']['time']['VALUE'] ?></sub>
                    <? else: ?>
                        <span>/</span>
                        <sub><?= $semStartTime ?></sub>
                    <? endif; ?>
                </div>
            <? endif; ?>
        </div>
        <? if (!empty($arResult["DETAIL_TEXT"])): ?>
            <h4><?= GetMessage("Описание") ?></h4>
            <?= $arResult["DETAIL_TEXT"]; ?>
        <? endif; ?>
        <? if (!empty($arResult["PROPERTIES"]["program_txt"]["~VALUE"]['TEXT'])): ?>
            <h4><?= GetMessage("Программа семинара") ?></h4>
            <?= $arResult["PROPERTIES"]["program_txt"]["~VALUE"]['TEXT']; ?>
        <? endif; ?>

        <? if (!empty($arResult['LECTORS'])): ?>
            <div class="clearfix">
                <h4 class="display-inline-block"><?= GetMessage($arResult["PROPERTIES"]["lecturer"]["NAME"]); ?></h4>
            </div>
            <ul class="learn-people-list">
            <? foreach ($arResult['LECTORS'] as $key => $arItem): ?>
                <li>
                    <?php $arLectorPicture = CFile::GetFileArray($arItem['DETAIL_PICTURE']); ?>
                    <? if(!empty($arLectorPicture['SRC']) && file_exists(realpath($_SERVER["DOCUMENT_ROOT"] . $arLectorPicture["SRC"]))): ?>
                        <?php $pic = CFile::ResizeImageGet($arLectorPicture, array("width" => 150, "height" => 150), BX_RESIZE_IMAGE_EXACT, true)['src']; ?>
                        <img src="<?= $pic ?>" alt=""/>
                    <? endif; ?>
                    <h3><?= $arItem['NAME']; ?></h3>
                    <span><?= $arItem['props']['company']['VALUE']; ?></span>
                    <span>–</span>
                    <i><?= $arItem['props']['status']['VALUE']; ?></i>
                </li>
            <? endforeach; ?>
            </ul>
        <? endif; ?>

        <? if (!empty($arResult['ORGS'])): ?>
            <div class="clearfix">
                <h4 class="display-inline-block"><?= $arResult["PROPERTIES"]["org"]["NAME"]; ?></h4>
            </div>
            <ul class="learn-people-list">
                <? foreach ($arResult['ORGS'] as $key => $arItem): ?>
                    <li>
                        <?php $arLectorPicture = CFile::GetFileArray($arItem['DETAIL_PICTURE']); ?>
                        <? if(!empty($arLectorPicture['SRC']) && file_exists(realpath($_SERVER["DOCUMENT_ROOT"] . $arLectorPicture["SRC"]))): ?>
                            <?php $pic = CFile::ResizeImageGet($arLectorPicture, array("width" => 150, "height" => 150), BX_RESIZE_IMAGE_EXACT, true)['src']; ?>
                            <img src="<?= $pic ?>" alt=""/>
                        <? endif; ?>
                        <h3><?= $arItem['NAME']; ?></h3>
                        <span><?= $arItem['props']['company']['VALUE']; ?></span>
                        <span>–</span>
                        <i><?= $arItem['props']['status']['VALUE']; ?></i>
                    </li>
                <? endforeach; ?>
            </ul>
        <? endif; ?>

        <? if (!empty($arResult['PROPERTIES']['address']['VALUE']) and $date_end > $now): ?>
            <h4><?= GetMessage("Адрес и контактная информация") ?></h4>

            <p class="light">
                <?= GetMessage("Адрес офиса") ?>: <?= $arResult['PROPERTIES']['address']['VALUE']; ?><br>
                <? if (!empty($arResult['PROPERTIES']['map']['VALUE'])): ?>
                    <?
                    $needCoords = $arResult['PROPERTIES']['map']['VALUE'];
                    $needCoords = explode(',', $needCoords);
                    ?>
                    <?= GetMessage("Наши координаты") ?>: <?= GetMessage("широта") ?>: <?= $needCoords[0] ?>’, <?= GetMessage("долгота") ?>: <?= $needCoords[1] ?>’
                    <br>
                <? endif; ?>
            </p>
            <? if (!empty($arResult['PROPERTIES']['map']['VALUE'])): ?>
                <script src="//api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
                <script>
                    var mapCenter = [<?=$arResult['PROPERTIES']['map']['VALUE']?>],
                        semName = '<?=$arResult["NAME"] ?>';
                </script>
                <script src="/h/js/sem-map.js"></script>
                <div class="map-cnt" id="map-learn">
                    <div id="map" style="height: 500px;width: 100%;"></div>
                </div>
            <? endif; ?>
            <? if (!empty($arResult['PROPERTIES']['public_transport']['~VALUE']['TEXT'])): ?>
                <div class="public_transport">
                    <p class="head icon-bus"><?= $arResult['PROPERTIES']['public_transport']['NAME'] ?>:</p>
                </div>
                <?= $arResult['PROPERTIES']['public_transport']['~VALUE']['TEXT'] ?>
            <? endif; ?>
        <? endif; ?>

        <? $APPLICATION->IncludeFile(
            "/local/include/share.php",
            array(
                "TITLE" => $arResult["NAME"],
                "DESCRIPTION" => !empty($arResult["PREVIEW_TEXT"]) ? $arResult["PREVIEW_TEXT"] : $arResult["DETAIL_TEXT"],
                "LINK" => $APPLICATION->GetCurPage(),
                "IMAGE" => $share_img_src
            )
        ); ?>
        <?

        ?>
        <? if ($date_end < $now && $date_end != '-10800' && LANGUAGE_ID != "en"): ?>
            <? $comments_cnt = $APPLICATION->IncludeComponent("kontora:element.list", "seminar_otziv", array(
                "IBLOCK_ID" => 23,
                "PROPS" => "Y",
                'FILTER' => array('PROPERTY_seminar_id' => $arResult['ID']),
                "ELEMENT_COUNT" => 3
            )); ?>
    
            <? if (count($comments_cnt) > 0): ?>
                <h4><?= GetMessage("Оставить отзыв") ?></h4>
            <? else: ?>
                <h4><?= GetMessage("Оставьте отзыв первым") ?></h4>
            <? endif; ?>
    
            <div id="add-new-comment">
                <? $APPLICATION->IncludeComponent(
                    "bitrix:iblock.element.add.form",
                    "seminar_otziv",
                    Array(
                        "COMPONENT_TEMPLATE" => ".default",
                        "IBLOCK_TYPE" => "training",
                        "IBLOCK_ID" => "23",
                        "STATUS_NEW" => "ANY",
                        "LIST_URL" => "",
                        "USE_CAPTCHA" => "N",
                        "USER_MESSAGE_EDIT" => "круто",
                        "USER_MESSAGE_ADD" => "",
                        "DEFAULT_INPUT_SIZE" => "30",
                        "RESIZE_IMAGES" => "N",
                        "PROPERTY_CODES" => array("353", "NAME", "PREVIEW_TEXT", '354', '355'),
                        "PROPERTY_CODES_REQUIRED" => array("353", "NAME", "PREVIEW_TEXT", '354', '355'),
                        "GROUPS" => array("2"),
                        "STATUS" => "ANY",
                        "ELEMENT_ASSOC" => "CREATED_BY",
                        "MAX_USER_ENTRIES" => "100000",
                        "MAX_LEVELS" => "100000",
                        "LEVEL_LAST" => "Y",
                        "MAX_FILE_SIZE" => "0",
                        "PREVIEW_TEXT_USE_HTML_EDITOR" => "N",
                        "DETAIL_TEXT_USE_HTML_EDITOR" => "N",
                        "SEF_MODE" => "N",
                        "CUSTOM_TITLE_NAME" => "ФИО",
                        "CUSTOM_TITLE_TAGS" => "",
                        "CUSTOM_TITLE_DATE_ACTIVE_FROM" => "",
                        "CUSTOM_TITLE_DATE_ACTIVE_TO" => "",
                        "CUSTOM_TITLE_IBLOCK_SECTION" => "",
                        "CUSTOM_TITLE_PREVIEW_TEXT" => "Отзыв",
                        "CUSTOM_TITLE_PREVIEW_PICTURE" => "",
                        "CUSTOM_TITLE_DETAIL_TEXT" => "",
                        "CUSTOM_TITLE_DETAIL_PICTURE" => "",
                        "ALLOW_EDIT" => "Y",
                        'SEMINAR_ID' => $arResult['ID'],
                        "AJAX_MODE" => 'Y'
                    )
                ); ?>
            </div>
        <? endif; ?>
    </div>
    <div class="col-md-3 aside">
        <? $APPLICATION->IncludeComponent("kontora:element.detail", "seminar-sidebar", array(
            "CACHE_TIME" => 0,
            "IBLOCK_ID" => $arParams["IBLOCK_ID"],
            "CODE" => $_REQUEST["ELEMENT_CODE"],
            "PROPS" => "Y",
            "PROPERTY_CODE" => array("adress"),
            "ADD_CHAIN_ITEM" => "N"
        ));?>
    </div>
</div>
