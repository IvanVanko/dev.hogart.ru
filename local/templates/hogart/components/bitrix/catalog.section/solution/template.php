<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<div class="inner no-full">
    <!--  <h1>Реализованные проекты</h1>    -->
    <h1><?= $arResult['NAME'] ?></h1>

    <!--  <h2>--><? //=$arResult['NAME']?><!--</h2>-->
    <? if (!empty($arResult['DESCRIPTION'])): ?>
        <?= $arResult['DESCRIPTION'] ?>
    <? endif; ?>

    <? foreach ($arResult['ELEMENTS'] as $count => $arElement): ?>
    <div class="inner no-full zones">
        <h2 class="label js-accordion" data-accordion="#complex_acc_0<?= $arElement["ID"]; ?>">
            <span class="paddingspan"><?= $arElement['NAME'] ?></span>
        </h2>

        <div id="complex_acc_0<?= $arElement["ID"]; ?>">
            <p>
                <?= $arElement['PREVIEW_TEXT'] ?>
            </p>

            <? if (!empty($arElement['PROPERTIES']['problem']['VALUE'])): ?>
                <p><?= $arElement['PROPERTIES']['problem']['~VALUE']['TEXT'] ?></p>
            <? endif; ?>

            <? if (!empty($arElement['PROPERTIES']['solution_project']['VALUE'])): ?>
                <p><?= $arElement['PROPERTIES']['solution_project']['~VALUE']['TEXT'] ?></p>
            <? endif; ?>
            <br/>

            <?if (!empty($arElement["PROPERTIES"]["pictures"]['VALUE']) || !empty($arElement["PROPERTIES"]["videos"]['VALUE'])):?>
            <hr>
            <ul class="sert-slider-cnt js-solutions-slider-<?=$count?>">
                <?
//                foreach ($arElement["PROPERTIES"]["pictures"]["VALUE"] as $picId):
                foreach ($arElement["PROPERTIES"]["pictures"]["VALUE"] as $key => $picId):
//                    $file = CFile::ResizeImageGet($picId, array('width'=>200, 'height'=>150), BX_RESIZE_IMAGE_EXACT, true);
//                    $file = CFile::ResizeImageGet($picId, array('width'=>320, 'height'=>180), BX_RESIZE_IMAGE_EXACT, true);
                    $file = CFile::ResizeImageGet($picId, array('width'=>480, 'height'=>360), BX_RESIZE_IMAGE_EXACT, true);
//                    $file = CFile::ResizeImageGet($picId, array('width'=>320, 'height'=>180), BX_RESIZE_IMAGE_EXACT, true);
//                    $fileBig = CFile::GetPath($picId);
                    $fileBig = CFile::ResizeImageGet($picId, array('width'=>1024, 'height'=>800), BX_RESIZE_IMAGE_PROPORTIONAL, true);


                    ?>
                    <li>
                        <div class="img-wrap">
                            <img class="js-popup-open-img" title="<?=$arElement['NAME']?>" data-group="gallG-<?=$count?>" src="<?= $file['src']; ?>" data-big-img="<?= $fileBig['src']; ?>" alt=""/>
                        </div>
                    </li>


                <?endforeach?>
                <?foreach ($arElement["PROPERTIES"]["videos"]["VALUE"] as $key => $picId) {?>
                    <li>
                        <div class="img-wrap video">
                            <img class="js-popup-open-img" title="<?=$arElement['NAME']?>" data-big-video="<?=$arElement["PROPERTIES"]["videos"]['VALUE'][$key]?>" src="http://img.youtube.com/vi/<?=$arElement["PROPERTIES"]["videos"]['VALUE'][$key]?>/mqdefault.jpg"  alt=""/>
                        </div>
                        <?if (!empty($arElement["PROPERTIES"]["videos"]['DESCRIPTION'][$key])) {?>
                        <div class="slider-comment">
                            <p><?=$arElement["PROPERTIES"]["videos"]['DESCRIPTION'][$key]?></p>
                        </div>
                        <?}?>
                    </li>
                <?}?>
            </ul>
            <?if (count($arElement["PROPERTIES"]["pictures"]["VALUE"]) + count($arElement["PROPERTIES"]["videos"]["VALUE"]) > 3):?>
                <div id="js-solutions-slider-<?=$count?>" class="control">
                    <span class="prev black"></span>
                    <span class="next black"></span>
                </div>
            <?else:?>
                <br/>
            <?endif;?>
        <?endif;?>
            <?if (!empty($arElement['PROPERTIES']['pres_mat']['VALUE'])):?>
                <div class="pres-material">
                    <h2><?=$arElement['PROPERTIES']['pres_mat']['NAME'];?></h2>
                    <ul>
                        <?foreach($arElement['PROPERTIES']['pres_mat']['VALUE'] as $key => $prez):?>
                            <?
                            $prez = CFile::GetFileArray($prez);
                            $fileType = explode('.',$prez['SRC']);
                            $fileSize = $prez['FILE_SIZE']/1024/1024;
                            ?>
                            <li><a href="<?= $prez['SRC'];?>"><?=$arElement['PROPERTIES']['pres_mat']['DESCRIPTION'][$key];?></a>&nbsp;
                                (<?= round($fileSize, 2);?> Mb, <?= $fileType[1];?>)
                            </li>
                        <?endforeach;?>
                    </ul>
                </div>
            <?endif;?>
        </div>
    </div>
        <script type="text/javascript">

            //                var width = $(this).width() / 3;
            setTimeout(function () {
                $('.js-solutions-slider-<?=$count?>').bxSlider({
                    minSlides: 3,
                    maxSlides: 3,
                    slideMargin: 22,
                    slideWidth: $(this).width() / 3 - 22,
                    pager: false,
                    nextText: '',
                    prevText: '',
                    nextSelector: $('#js-solutions-slider-<?=$count?>').find('.next'),
                    prevSelector: $('#js-solutions-slider-<?=$count?>').find('.prev'),
                    infiniteLoop: false
//        responsive:true
                });
                $(window).resize();
            }, 200);


        </script>
    <? endforeach; ?>

    <? if (!empty($arResult['ZONES'])): ?>
        <div class="inner no-full zones">
            <h2 class="label js-accordion" data-accordion="#complex_acc_02">
                <span class="paddingspan">Комплектация специализированных зон</span>
            </h2>

            <div id="complex_acc_02">
                <ul class="special-zones">
                    <? foreach ($arResult['ZONES'] as $arZone): ?>
                        <li>
                            <a href="<?= $arZone['SECTION_PAGE_URL'] ?>">
                                <span class="head"><?= $arZone['NAME'] ?></span>

                                <img src="<?= CFile::GetPath($arZone['PICTURE']) ?>"/>
                            </a>
                        </li>
                    <? endforeach; ?>
                </ul>
            </div>
        </div>
    <? endif; ?>
    <?$APPLICATION->IncludeFile(
        "/local/include/share.php",
        array(
            "TITLE" => $arResult["NAME"],
            "DESCRIPTION" => !empty($arResult["DESCRIPTION"]),
            "LINK" => $APPLICATION->GetCurPage(),
            "IMAGE"=> $share_img_src
        )
    );?>
</div>
    <aside class="sidebar js-fh js-fixed-block js-paralax-height" data-fixed="top">
        <div class="inner js-paralax-item">
            <div class="padding">
                <h2><?= GetMessage("Наши проекты") ?></h2>

                <div class="preview-project-viewport">
                    <div class="preview-project-viewport-inner">
                        <ul class="preview-project">
                            <? foreach ($arResult['PROJECTS'] as $arProject): ?>
                                <li class="text-center">
                                    <a href="<?= SITE_DIR ?>integrated-solutions/<?= $arParams['SECTION_CODE'] ?>/<?= $arProject['CODE'] ?>/">
                                        <?if (!empty($arProject['PREVIEW_PICTURE'])):?>
                                            <?$file = CFile::ResizeImageGet($arProject['PREVIEW_PICTURE'], array('width'=>215, 'height'=>111), BX_RESIZE_IMAGE_EXACT, true);  ?>
                                            <img src="<?=$file['src']?>" alt="">
                                        <?else:?>
                                            <img class="grayscale" src="/images/project_no_img.jpg" alt="">
                                        <?endif;?>
                                        <span class="proj-txt">
                                            <span class="head"><?= $arProject['NAME'] ?></span>
                                        </span>
                                    </a>
                                </li>
                            <? endforeach; ?>
                        </ul>
                    </div>
                </div>
                <?if (count($arResult['PROJECTS'])>0):?>
                <a class="complex-link" href="<?= SITE_DIR ?>integrated-solutions/all_projects.php">Все проекты</a>
                <?endif;?>
            </div>
            <?$APPLICATION->IncludeComponent("bitrix:main.include","",Array(
                    "AREA_FILE_SHOW" => "page",
                    "AREA_FILE_SUFFIX" => "inc_podpis",
                    "AREA_FILE_RECURSIVE" => "Y",
                    "EDIT_TEMPLATE" => "standard.php"
                )
            );?>
        </div>
    </aside>