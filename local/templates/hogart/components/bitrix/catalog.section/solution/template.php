<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<div class="row">
    <div class="col-md-9">
        <h3><?= $arResult['NAME'] ?></h3>
        <? if (!empty($arResult['DESCRIPTION'])): ?>
            <?= $arResult['DESCRIPTION'] ?>
        <? endif; ?>

        <? foreach ($arResult['ELEMENTS'] as $count => $arElement): ?>
            <div class="zones">
                <h4 data-toggle="collapse" data-target="#collapse-<?= $arElement["ID"]; ?>" aria-expanded="false" aria-controls="collapse-elements">
                    <span class="paddingspan"><?= $arElement['NAME'] ?></span>
                </h4>

                <div class="collapse" id="collapse-<?= $arElement["ID"]; ?>">
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

                    <? if (!empty($arElement["PROPERTIES"]["pictures"]['VALUE']) || !empty($arElement["PROPERTIES"]["videos"]['VALUE'])): ?>
                        <hr>
                        <? if (count($arElement["PROPERTIES"]["pictures"]["VALUE"]) + count($arElement["PROPERTIES"]["videos"]["VALUE"]) > 3): ?>
                            <div id="js-solutions-slider-<?= $arElement["ID"]; ?>" class="controls text-right">
                                <div class="prev"><i class="fa fa-arrow-circle-o-left"></i></div>
                                <div class="next"><i class="fa fa-arrow-circle-o-right"></i></div>
                            </div>
                        <? endif; ?>
                        <ul class="sert-slider-cnt js-solutions-slider-<?= $arElement["ID"]; ?>">
                            <?
                            foreach ($arElement["PROPERTIES"]["pictures"]["VALUE"] as $key => $picId):
                                $file = CFile::ResizeImageGet($picId, array('width' => 480, 'height' => 360), BX_RESIZE_IMAGE_EXACT, true);
                                $fileBig = CFile::ResizeImageGet($picId, array('width' => 1024, 'height' => 800), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                                ?>
                                <li>
                                    <div class="img-wrap">
                                        <img class="js-popup-open-img" title="<?= $arElement['NAME'] ?>"
                                             data-group="gallG-<?= $count ?>" src="<?= $file['src']; ?>"
                                             data-big-img="<?= $fileBig['src']; ?>" alt=""/>
                                    </div>
                                </li>
                            <? endforeach ?>
                            <? foreach ($arElement["PROPERTIES"]["videos"]["VALUE"] as $key => $picId) { ?>
                                <li>
                                    <div class="img-wrap video">
                                        <img class="js-popup-open-img" title="<?= $arElement['NAME'] ?>"
                                             data-big-video="<?= $arElement["PROPERTIES"]["videos"]['VALUE'][$key] ?>"
                                             src="http://img.youtube.com/vi/<?= $arElement["PROPERTIES"]["videos"]['VALUE'][$key] ?>/mqdefault.jpg"
                                             alt=""/>
                                    </div>
                                    <? if (!empty($arElement["PROPERTIES"]["videos"]['DESCRIPTION'][$key])) { ?>
                                        <div class="slider-comment">
                                            <p><?= $arElement["PROPERTIES"]["videos"]['DESCRIPTION'][$key] ?></p>
                                        </div>
                                    <? } ?>
                                </li>
                            <? } ?>
                        </ul>
                    <? endif; ?>
                    <? if (!empty($arElement['PROPERTIES']['pres_mat']['VALUE'])): ?>
                        <div class="pres-material">
                            <h4><?= $arElement['PROPERTIES']['pres_mat']['NAME']; ?></h4>
                            <ul>
                                <? foreach ($arElement['PROPERTIES']['pres_mat']['VALUE'] as $key => $prez): ?>
                                    <?
                                    $prez = CFile::GetFileArray($prez);
                                    $fileType = explode('.', $prez['SRC']);
                                    $fileSize = $prez['FILE_SIZE'] / 1024 / 1024;
                                    ?>
                                    <li>
                                        <a href="<?= $prez['SRC']; ?>"><?= $arElement['PROPERTIES']['pres_mat']['DESCRIPTION'][$key]; ?></a>&nbsp;
                                        (<?= round($fileSize, 2); ?> Mb, <?= $fileType[1]; ?>)
                                    </li>
                                <? endforeach; ?>
                            </ul>
                        </div>
                    <? endif; ?>
                </div>
            </div>
            <script type="text/javascript">
                if (bxSlider typeof  == "undefined") {
                    var bxSlider = {};
                }
                $('#collapse-<?= $arElement["ID"]; ?>').on('show.bs.collapse', function () {
                    if (!bxSlider[<?= $arElement["ID"]; ?>]) {
                        bxSlider[<?= $arElement["ID"]; ?>] = $('.js-solutions-slider-<?= $arElement["ID"]; ?>').bxSlider({
                            minSlides: 3,
                            maxSlides: 3,
                            slideMargin: 22,
                            slideWidth: $(this).width() / 3 - 22,
                            pager: false,
                            nextText: '',
                            prevText: '',
                            nextSelector: $('#js-solutions-slider-<?= $arElement["ID"]; ?>').find('.next'),
                            prevSelector: $('#js-solutions-slider-<?= $arElement["ID"]; ?>').find('.prev'),
                            infiniteLoop: false
                        });
                    }
                });
            </script>
        <? endforeach; ?>

        <? if (!empty($arResult['ZONES'])): ?>
            <div class="inner no-full zones">
                <h3 class="label js-accordion" data-accordion="#complex_acc_02">
                    <span class="paddingspan">Комплектация специализированных зон</span>
                </h3>

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
        <? $APPLICATION->IncludeFile(
            "/local/include/share.php",
            array(
                "TITLE" => $arResult["NAME"],
                "DESCRIPTION" => !empty($arResult["DESCRIPTION"]),
                "LINK" => $APPLICATION->GetCurPage(),
                "IMAGE" => $share_img_src
            )
        ); ?>
    </div>
    <div class="col-md-3 aside">
        <h3><?= GetMessage("Наши проекты") ?></h3>

        <div class="preview-project-viewport">
            <div class="preview-project-viewport-inner">
                <ul class="preview-project">
                    <? foreach ($arResult['PROJECTS'] as $arProject): ?>
                        <li class="text-center">
                            <a href="<?= SITE_DIR ?>integrated-solutions/<?= $arParams['SECTION_CODE'] ?>/<?= $arProject['CODE'] ?>/">
                                <? if (!empty($arProject['PREVIEW_PICTURE'])): ?>
                                    <? $file = CFile::ResizeImageGet($arProject['PREVIEW_PICTURE'], array('width' => 215, 'height' => 111), BX_RESIZE_IMAGE_EXACT, true); ?>
                                    <img src="<?= $file['src'] ?>" alt="">
                                <? else: ?>
                                    <img class="grayscale" src="/images/project_no_img.jpg" alt="">
                                <? endif; ?>
                                <span class="proj-txt">
                                            <span class="head"><?= $arProject['NAME'] ?></span>
                                        </span>
                            </a>
                        </li>
                    <? endforeach; ?>
                </ul>
            </div>
        </div>
        <? if (count($arResult['PROJECTS']) > 0): ?>
            <a class="complex-link"
               href="<?= SITE_DIR ?>integrated-solutions/all_projects.php"><?= GetMessage("Все проекты") ?></a>
        <? endif; ?>
    </div>
</div>