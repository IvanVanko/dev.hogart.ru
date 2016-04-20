<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$date_from = FormatDate("d F Y", MakeTimeStamp($arResult["ACTIVE_FROM"]));
$share_img_src = false;?>
<?//pr($arResult);?>
    <div class="inner">
        <!-- <?$APPLICATION->IncludeComponent("bitrix:menu", "section_menu", Array(
                "ROOT_MENU_TYPE" => "left",
                "MAX_LEVEL" => "1",
                "CHILD_MENU_TYPE" => "left",
                "USE_EXT" => "Y",
                "DELAY" => "N",
                "ALLOW_MULTI_SELECT" => "Y",
                "MENU_CACHE_TYPE" => "N",
                "MENU_CACHE_TIME" => "3600",
                "MENU_CACHE_USE_GROUPS" => "Y",
                "MENU_CACHE_GET_VARS" => ""
            )
        );?> -->

        <div class="control control-action">

            <? /* if (isset($arResult['PREV'])):?>
                <span class="prev black"><a href="<?=$arResult['PREV']?>"></a></span>
            <?endif;?>
            <?if (isset($arResult['NEXT'])):?>
                <span class="next black"><a href="<?=$arResult['NEXT']?>"></a></span>
            <?endif; */ ?>
            <? if(isset($arResult['PREV'])): ?><span class="prev black"><a href="<?=$arResult['PREV']?>"></a></span><? endif; ?>
            <? if(isset($arResult['NEXT'])): ?><span class="next black"><a href="<?=$arResult['NEXT']?>"></a></span><? endif; ?>

        </div>
        <h1><?$APPLICATION->ShowTitle()?></h1>

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
                    <div class="control">
                        <span class="prev black" id="galP"></span>
                        <span class="next black" id="galN"></span>
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
        <a class="back_page icon-news-back" href="/company/news/">Назад к новостям</a>
    </div>
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