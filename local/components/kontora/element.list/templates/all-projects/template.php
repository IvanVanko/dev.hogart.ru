<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
    <div class="all-project">
        <ul class="all-project-box">
            <?foreach ($arResult["ITEMS"] as $arItem): //Цикл по всем элементам?>
                <li>
                    <?$file = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE']['ID'], array('width' => 175,
                        'height' => 115), BX_RESIZE_IMAGE_EXACT, true);?>
                    <a href="<?=$arItem['SECTION_CODE']?>/<?=$arItem['CODE']?>/">
                        <? if (!empty($arItem["PREVIEW_PICTURE"])): ?>
                            <img src="<?=$file['src'];?>" alt="<?=$arItem['NAME']?>"/>
                        <? endif; ?>
                        <span><?=$arItem['NAME']?></span>
                    </a>
                </li>

            <?endforeach;?>
            <li class="none"></li><li class="none"></li><li class="none"></li><li class="none"></li><li class="none"></li>
        </ul>
    </div>