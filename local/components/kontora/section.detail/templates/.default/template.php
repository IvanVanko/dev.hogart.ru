<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?=$arResult['NAME']?>
<? if (!empty($arResult["DETAIL_PICTURE"])): ?>
	<img src="<?=$arResult['DETAIL_PICTURE']?>" alt="<?=$arResult['NAME']?>"/>
<? endif; ?>
<? if ($arResult["DESCRIPTION"]): ?>
	<?=$arResult["DESCRIPTION"]?>
<? endif; ?>
<a href="<?=$arResult['LIST_PAGE_URL']?>"> Вернуться в раздел</a>