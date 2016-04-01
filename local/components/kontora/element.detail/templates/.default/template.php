<?php

/*
$arResult - массив элемента с ключами:
$arResult['NAME']				Название элемента
$arResult['DETAIL_PAGE_URL']	Url детальной страницы
$arResult['PREVIEW_PICTURE']	Путь до каринки анонса
$arResult['PREVIEW_TEXT']		Описание анонса
$arResult['DETAIL_PICTURE']	Путь до детальной картинки
$arResult['DETAIL_TEXT']		Детальное описание
$arResult['DATE_ACTIVE_FROM']	Дата начала активности элемента
$arResult['DATE_ACTIVE_TO']	Дата окончания активности элемента

Если нужно вывести свойства, в вызове компонента необходимо указать параметр 'PROPS' => 'Y'
$arResult['PROPERTIES']['символьный_код_свойства']['VALUE']
(если свойство множественное, то необходимо сделать цикл по $arResult['PROPERTIES']['код_свойства']['VALUE'])
Символьный код свойства можно посмотреть в административной части сайта. 
Контент - Инфоблок - Типры инфоблоков (выбираме нужный тип и инфоблок, нажимаем изменить). Во вкладке "Свойства"" ищем необходимое свойство.
*/

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if (!isset($arParams['ITEM_TEMPLATE']) && empty($arParams['ITEM_TEMPLATE'])):?>
	<h2><?=$arResult['NAME']?></h2>
	<? if (!empty($arResult["DETAIL_PICTURE"])): ?>
		<img src="<?=$arResult['DETAIL_PICTURE']?>" alt="<?=$arResult['NAME']?>"/>
	<? endif; ?>
	<? if ($arResult["DETAIL_TEXT"]): ?>
		<?=$arResult["DETAIL_TEXT"]?>
	<? endif; ?>
	<a href="<?=$_SERVER['REFERER']?>">Вернуться на страницу списка</a>
<? else:
	echo htmlspecialchars_decode(str_replace(explode(",", "^".implode("^,^", array_keys($arResult))."^"), array_values($arResult), $arParams['ITEM_TEMPLATE']));
endif; ?>
			