<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? if (!empty($arResult["ITEMS"])): ?>
    <ul class="sert-slider-cnt js-itegr-slider2">
        <? foreach ($arResult["ITEMS"] as $arItem): //Цикл по всем элементам?>
            <li class="text-center">
                <? $file = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE']['ID'], array('width' => 412, 'height' => 271), BX_RESIZE_IMAGE_EXACT, true); ?>
                <a href="/integrated-solutions/<?= $arItem['SECTION_CODE'] ?>/<?= $arItem['CODE'] ?>/">
                    <? if (!empty($arItem["DETAIL_PICTURE"])): ?>
                        <img src="<?= $file['src'] ?>" alt="<?= $arItem['NAME'] ?>" style="width: 100%;"/>
                    <? endif; ?>
                    <p class="caruseltextcomm"><?= $arItem['NAME'] ?></p>
                </a>
            </li>
        <? endforeach; ?>
    </ul>
    <? if (count($arResult['ITEMS']) > 3): ?>
        <div id="js-control-itegr2" class="controls text-right">
            <div class="prev" id="prevT">
                <i class="fa fa-arrow-circle-o-left"></i>
            </div>
            <div class="next" id="nextT">
                <i class="fa fa-arrow-circle-o-right"></i>
            </div>
        </div>
    <? endif; ?>
<? endif; ?>