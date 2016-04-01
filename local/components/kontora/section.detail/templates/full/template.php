<?php

/*
$arResult - массив секции с ключами:
$arResult['NAME']				Название секции
$arResult['SECTION_PAGE_URL']	Url детальной страницы
$arResult['PICTURE']			Путь до каринки
$arResult['DESCRIPTION']		Описание
$arResult['DETAIL_PICTURE']		Путь до детальной картинки
*/

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if (!isset($arParams['ITEM_TEMPLATE']) && empty($arParams['ITEM_TEMPLATE'])):?>
	<?=$arResult['NAME']?>
	<? if (!empty($arResult["DETAIL_PICTURE"])): ?>
		<img src="<?=$arResult['DETAIL_PICTURE']?>" alt="<?=$arResult['NAME']?>"/>
	<? endif; ?>
	<? if ($arResult["DESCRIPTION"]): ?>
		<?=$arResult["DESCRIPTION"]?>
	<? endif; ?>
	<a href="<?=$arResult['LIST_PAGE_URL']?>"> Вернуться в раздел</a>
<? else:
	echo htmlspecialchars_decode(str_replace(explode(",", "^".implode("^,^", array_keys($arResult))."^"), array_values($arResult), $arParams['ITEM_TEMPLATE']));
endif; ?>