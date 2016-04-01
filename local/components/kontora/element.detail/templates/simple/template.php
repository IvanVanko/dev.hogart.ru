<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<h2><?=$arResult['NAME']?></h2>
<? if (!empty($arResult["DETAIL_PICTURE"])): ?>
	<img src="<?=$arResult['DETAIL_PICTURE']?>" alt="<?=$arResult['NAME']?>"/>
<? endif; ?>
<? if ($arResult["DETAIL_TEXT"]): ?>
	<?=$arResult["DETAIL_TEXT"]?>
<? endif; ?>
<a href="<?=$_SERVER['REFERER']?>">Вернуться на страницу списка</a>