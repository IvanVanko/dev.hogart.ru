<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? if (count($arResult['ITEMS']) > 0): ?>
    <div class="inner no-padding">
        <div class="video-block">
            <? foreach ($arResult['ITEMS'] as $key => $arItem): ?>
                <? if (empty($arItem["PROPERTIES"]['video']['VALUE'])): ?>
                    <div class="video-item img <? if ($key % 3 == 0): ?>big<? else: ?>small<?endif; ?>"><img
                            src="<?= $arItem['PREVIEW_PICTURE']['SRC'] ?>" class="js-popup-open-img" alt=""></div>
                <? else: ?>
                    <div class="video-item <? if ($key % 3 == 0): ?>big<? else: ?>small<?endif; ?> js-popup-open-video">
                        <iframe width="100%" height="100%"
                                src="https://www.youtube.com/embed/<?= $arItem["PROPERTIES"]['video']['VALUE'] ?>?rel=0"
                                frameborder="0" allowfullscreen></iframe>
                        <? if (!empty($arItem['PREVIEW_PICTURE']['SRC'])): ?>
                            <img src="<?= $arItem['PREVIEW_PICTURE']['SRC'] ?>" alt="">
                        <?endif; ?>
                    </div>
                <?endif; ?>
            <? endforeach; ?>
        </div>
    </div>
<? endif; ?>