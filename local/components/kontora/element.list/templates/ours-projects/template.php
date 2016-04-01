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
?>


<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if (count($arResult['ITEMS']) < 2):?>
    <pre>
<!--    --><?//var_dump($arResult['ITEMS'][0]);?>
    </pre>
    <ul class="sert-slider-cnt js-normal-slider">
        <li class="text-center">
            <a href="<?=$arResult['ITEMS'][0]["DETAIL_PAGE_URL"]?>">
                <? if (!empty($arResult['ITEMS'][0]["DETAIL_PICTURE"])): ?>
                    <img src="<?=$arResult['ITEMS'][0]['DETAIL_PICTURE']['SRC']?>" alt="<?=$arResult['ITEMS'][0]['NAME']?>" style="width: 100%;"/>
                <? endif; ?>
                <p class="caruseltextcomm"><?=$arResult['ITEMS'][0]['NAME']?></p>
            </a>
        </li>
    </ul>
    <?else:?>
    <?
	if (isset($arParams['HTML_TYPE']))
		echo '<'.$arParams['HTML_TYPE'][0].'>';
	else
		echo '<ul class="sert-slider-cnt js-normal-slider" data-next="#nextT" data-prev="#prevT">';
		foreach ($arResult["ITEMS"] as $arItem): //Цикл по всем элементам
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>
			<? if (isset($arParams['HTML_TYPE']))
				echo '<'.$arParams['HTML_TYPE'][1].' id="'.$this->GetEditAreaId($arItem['ID']).'">';
			else
				echo '<li id="'.$this->GetEditAreaId($arItem['ID']).'" class="text-center">';
				if (!isset($arParams['ITEM_TEMPLATE']) && empty($arParams['ITEM_TEMPLATE'])):?>
					<a href="<?=$arItem["DETAIL_PAGE_URL"]?>">
					<? if (!empty($arItem["DETAIL_PICTURE"])): ?>
                        <img src="<?=$arItem['DETAIL_PICTURE']['SRC']?>" alt="<?=$arItem['NAME']?>"/ class="imgsliderT">
                        <p class="caruselTtext">
                            <?=$arItem['NAME']?>
                        </p>
					<? endif; ?>
                    </a>

				<? else:
					echo htmlspecialchars_decode(str_replace(explode(",", "^".implode("^,^", array_keys($arItem))."^"), array_values($arItem), $arParams['ITEM_TEMPLATE']));
				endif;?>
			<? if (isset($arParams['HTML_TYPE']))
				echo '<'.$arParams['HTML_TYPE'][2].'>';
			else
				echo '</li>';
		endforeach;?>
	<? if (isset($arParams['HTML_TYPE']))
		echo '<'.$arParams['HTML_TYPE'][3].'>';
	else
		echo '</ul>';?>
    <div class="control">
        <span class="prev black" id="prevT"></span>
        <span class="next black" id="nextT"></span>
    </div>
    <?
	if ($arParams["NAV"] == 'Y')
		echo $arResult["NAV_STRING"];
endif; ?>