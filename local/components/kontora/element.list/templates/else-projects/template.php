<?php

/*
$arResult['ITEMS'] - массив, содержащий в себе массив из элементов со значениями их полей и свойств
$arItem - массив элемента с ключами:
$arItem['NAME']				Название элемента
$arItem['DETAIL_PAGE_URL']	Url детальной страницы
$arItem['PREVIEW_PICTURE']	Путь до каринки анонса
$arItem['PREVIEW_TEXT']		Описание анонса
$arItem['DETAIL_PICTURE']	Путь до детальной картинки
$arItem['DETAIL_TEXT']		Детальное описание
$arItem['DATE_ACTIVE_FROM']	Дата начала активности элемента
$arItem['DATE_ACTIVE_TO']	Дата окончания активности элемента

Если нужно вывести дополнительные свойства, в вызове компонента необходимо указать параметр 'PROPS' => 'Y'
$arItem['PROPERTIES']['код_свойства']['VALUE']
(если свойство множественное, то необходимо сделать цикл по $arItem['PROPERTIES']['код_свойства']['VALUE'])
Символьный код свойства можно посмотреть в административной части сайта. 
Контент - Инфоблок - Типры инфоблоков (выбираем нужный тип и инфоблок, нажимаем изменить). Во вкладке "Свойства" ищем необходимое свойство.
*/

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
?>
<?if (!empty($arResult["ITEMS"])):?>
    <ul class="sert-slider-cnt js-itegr-slider2">
        <?foreach ($arResult["ITEMS"] as $arItem): //Цикл по всем элементам?>
            <li class="text-center">
                <?$file = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE']['ID'], array('width' => 412, 'height' => 271), BX_RESIZE_IMAGE_EXACT, true);?>
                <a href="/integrated-solutions/<?=$arItem['SECTION_CODE']?>/<?=$arItem['CODE']?>/">
                    <? if (!empty($arItem["DETAIL_PICTURE"])): ?>
                        <img src="<?=$file['src']?>" alt="<?=$arItem['NAME']?>" style="width: 100%;"/>
                    <? endif; ?>
                    <p class="caruseltextcomm"><?=$arItem['NAME']?></p>
                </a>
            </li>
        <?endforeach;?>
    </ul>
    <?if (count($arResult['ITEMS']) > 3):?>
    <div id="js-control-itegr2" class="control text-right">
        <span class="prev black" id="prevT"></span>
        <span class="next black" id="nextT"></span>
    </div>
    <?endif;?>
<?endif;?>