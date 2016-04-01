<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>
<? if (count($arResult["ITEMS"]) > 0): ?>
<ul class="inner_menu big-menu">
	<?foreach ($arResult["ITEMS"] as $arItem):?>
	<?#DebugMessage($arItem);?>
	<li><a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a></li>
	<?endforeach;?>
</ul>
<?endif;?>
	<?$APPLICATION->IncludeFile(
		INCLUDE_AREAS."block-news-bottom-menu.php",
		Array(),
		Array("MODE"=>"html", "NAME"=>"Right Panel")
	);?>

<?
#DebugMessage($this);
?>