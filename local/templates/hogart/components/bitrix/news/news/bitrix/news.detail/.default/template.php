<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$date_from = FormatDate("d F Y", MakeTimeStamp($arResult["ACTIVE_FROM"]));
$share_img_src = false;
?>
<div class="row">
    <div class="col-md-9">
        <div class="row vertical-align">
            <div class="col-md-10">
                <h3 style="margin-top: 10px"><?$APPLICATION->ShowTitle()?></h3>
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

        <div class="news-one-cnt">
            <div class="padding-news">
                <div class="date">
                    <sub><?= $date_from ?></sub>
                </div>
                <? if (!empty($arResult['PREVIEW_TEXT'])): ?><p><?= $arResult['PREVIEW_TEXT'] ?></p><? endif; ?>
            </div>

            <? if (!empty($arResult['DETAIL_PICTURE']['SRC'])):
                $share_img_src = $arResult['DETAIL_PICTURE']['SRC'];?>
                <div class="news-detail-pic">
                    <div class="img-wrap">
                        <img class="js-popup-open-img" title="<?=$arResult['NAME']?>" src="<?= $arResult['DETAIL_PICTURE']['SRC'] ?>" alt=""/>
                    </div>
                </div>
            <? endif; ?>

            <div class="padding-news">
                <?= $arResult['DETAIL_TEXT'] ?>

                <?if (!empty($arResult['PROPERTIES']['photogallery']['VALUE'])): ?><h2>Фотогалерея</h2><? endif; ?>
            </div>
            <div class="row gallery-slider" id="galElWidth">
                <ul class="js-normal-slider-init" style="min-width: 500px" data-width="#galElWidth" data-next="#galN" data-prev="#galP">
                    <? $countGalImg = 0;?>
                    <li>
                        <div class="gall-news-one">
                            <?
                            foreach ($arResult['PROPERTIES']['photogallery']['VALUE'] as $key => $photo):
                                $photoBig = CFile::GetPath($photo);
                                $photo = CFile::ResizeImageGet($photo, array('width'=>320, 'height'=>320), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                                if  (!$key) {
                                    $share_img_src = $photo['src'];
                                }
                                ?>

                                <div class="img-wrap">
                                    <img class="js-popup-open-img" title="<?=$arResult['NAME']?>" data-big-img="<?=$photoBig?>" data-group="gallG" src="<?= $photo['src'] ?>" alt=""/>
                                </div>
                                <?$countGalImg++;
                                if($countGalImg%4==0){
                                    $countGalImg = 0;
                                    echo '</div></li>';
                                    if($countGalImg!=count($arResult['PROPERTIES']['photogallery']['VALUE']))
                                        echo '<li><div class="gall-news-one">';
                                }
                                ?>
                            <? endforeach; ?>
                        </div>
                    </li>
                </ul>
                <? if (count($arResult['PROPERTIES']['photogallery']['VALUE']) > 4) { ?>
                    <div class="controls">
                        <div class="prev" id="galP"><i class="fa fa-arrow-circle-o-left"></i></div>
                        <div class="next" id="galN"><i class="fa fa-arrow-circle-o-right"></i></div>
                    </div>
                <?}?>
            </div>
            <? /*endif*/ ?>

        </div>
        <?$APPLICATION->IncludeFile(
            "/local/include/share.php",
            array(
                "TITLE" => $arResult["NAME"],
                "DESCRIPTION" => !empty($arResult["PREVIEW_TEXT"]) ? $arResult["PREVIEW_TEXT"]:$arResult["DETAIL_TEXT"],
                "LINK" => $APPLICATION->GetCurPage(),
                "IMAGE"=> $share_img_src
            )
        );?>
        <a class="back_page icon-news-back" href="/company/news/"><?= GetMessage("Назад к новостям") ?></a>
    </div>
    <div class="col-md-3 aside">
        <?$APPLICATION->IncludeComponent(
            "kontora:element.list",
            "news_detail",
            Array(
                "IBLOCK_ID" => 3,
                'FILTER' => array('!ID' => $arResult['ID'], 'PROPERTY_catalog_section' => $arResult['PROPERTIES']['catalog_section']['VALUE']),
                'ELEMENT_COUNT' => 4,
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "0",
                'ORDER' => array('active_from' => 'desc'),
                "PROPS" => "Y",
                "CHECK_PERMISSIONS" => "Y",
            )
        );?>
    </div>
</div>
