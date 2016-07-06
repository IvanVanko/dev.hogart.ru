<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<div class="preview-project-viewport">
    <div class="preview-project-viewport-inner">
        <ul class="preview-project">
            <? foreach ($arResult["ITEMS"] as $arItem): //Цикл по всем элементам?>
                <li class="text-center">
                    <? $file = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE']['ID'], array('width' => 412, 'height' => 271), BX_RESIZE_IMAGE_EXACT, true); ?>
                    <a href="<?= SITE_DIR ?>integrated-solutions/<?= $arItem['SECTION_CODE'] ?>/<?= $arItem['CODE'] ?>/">
                        <? if (!empty($arItem["PREVIEW_PICTURE"])): ?>
                            <img src="<?= $file['src'] ?>" alt="<?= $arItem['NAME'] ?>"/>
                        <? endif; ?>
                        <p><?= $arItem['NAME'] ?></p>
                    </a>
                </li>
            <? endforeach; ?>
        </ul>
    </div>
</div>