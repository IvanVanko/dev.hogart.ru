<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<div class="inner no-full">
    <!--  <h1>Зоны</h1>    -->
    <h1><?= $arResult['NAME'] ?></h1>  

    <!--  <h2>--><? //=$arResult['NAME']?><!--</h2>-->
    <?= $arResult['DESCRIPTION'] ?>

    <? foreach ($arResult['ELEMENTS'] as $key => $arElement): ?>
        <div class="inner no-full zones">
            <h2 class="label"><span class="paddingspan"><?= $arElement['NAME'] ?></span></h2>
            <div class="zones-wrap">
            <p>
                <?= $arElement['PREVIEW_TEXT'] ?>
            </p>
            <? if (!empty($arElement['PROPERTIES']['pictures']['VALUE'])): ?>
                <ul class="images-slider<?= $key?> js-normal-slider" data-next=".js-control-cnt<?= $key?> .next"
                    data-prev=".js-control-cnt .prev" style="height: 362px;">
                    <? foreach ($arElement['PROPERTIES']['pictures']['VALUE'] as $picId): ?>
                        <li>
                            <div class="img-wrap">
                                <img class="js-popup-open-img" title="<?=$arElement['NAME']?>" data-group="gallG<?= $key?>"
                                     src="<?= CFile::GetPath($picId)?>" alt=""/>
                            </div>
                        </li>
                    <? endforeach; ?>
                </ul>
            <? endif; ?>
            <? if (count($arElement['PROPERTIES']['pictures']['VALUE']) > 1): ?>
                <div class="control js-control-cnt<?= $key?>">
                    <span class="prev black"><a class="bx-prev" href=""></a></span>
                    <span class="next black"><a class="bx-next" href=""></a></span>
                </div>
            <? endif; ?>

            <? if (!empty($arElement['PROPERTIES']['problem']['VALUE'])): ?>
                <h3>Задача</h3>
                <p><?= $arElement['PROPERTIES']['problem']['~VALUE']['TEXT'] ?></p>
            <? endif; ?>

            <? if (!empty($arElement['PROPERTIES']['solution_project']['VALUE'])): ?>
                <h3>Решение</h3>
                <p><?= $arElement['PROPERTIES']['solution_project']['~VALUE']['TEXT'] ?></p>
            <? endif; ?>
                <br/>
                <hr>
            </div>
        </div>

    <? endforeach; ?>
    </div>

    <aside class="sidebar js-fh js-fixed-block js-paralax-height" data-fixed="top">
        <div class="inner js-paralax-item">
            <div class="padding">
                <? if (!empty($arResult['PROJECTS'])): ?>
                    <h2>Наши проекты</h2>
                    <div class="preview-project-viewport">
                        <div class="preview-project-viewport-inner">
                            <ul class="preview-project">
                                <? foreach ($arResult['PROJECTS'] as $arProject): ?>
                                    <li class="text-center">
                                        <a href="/integrated-solutions/<?= $arProject['SECTION_CODE'] ?>/<?= $arProject['CODE'] ?>/">
                                            <?
                                            $file = CFile::ResizeImageGet($arProject['PREVIEW_PICTURE'], array('width'=>320, 'height'=>180), BX_RESIZE_IMAGE_EXACT, true);
                                            ?>
<!--                                            <img src="--><?//= CFile::GetPath($arProject['PREVIEW_PICTURE']) ?><!--" alt="">-->
                                            <img src="<?= $file['src'] ?>" alt="">
                    <span class="proj-txt">
                        <span class="head"><?= $arProject['NAME'] ?></span>
                    </span>
                                        </a>
                                    </li>
                                <? endforeach; ?>
                            </ul>
                        </div>
                    </div>
                <? endif; ?>
            </div>
        </div>
    </aside>
    <script type="text/javascript">
        $(window).resize(function () {
            $('.zones').each(function () {
                var h2H = $(this).find('h2').outerHeight()+40,
                    hZONEwrap = $(this).find('.zones-wrap').outerHeight();
                $(this).attr('data-zone-h', hZONEwrap+h2H);
                $(this).attr('data-h2h', h2H);
                if($(this).find('h2').hasClass('active')){
                    $(this).css('height', hZONEwrap+h2H+'px');
                } else {
                    $(this).css('height', h2H+'px');

                }


            });
        });

    </script>