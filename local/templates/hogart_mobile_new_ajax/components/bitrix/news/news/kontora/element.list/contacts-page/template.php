<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>
<? if (count($arResult["ITEMS"]) > 0): ?>
<ul class="inner_menu big-menu">
	<?foreach ($arResult["ITEMS"] as $arItem):?>
	<li><a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a></li>
	<?endforeach;?>
</ul>
<?$APPLICATION->IncludeFile(
	INCLUDE_AREAS."block-news-bottom-menu.php",
	Array(),
	Array("MODE"=>"html", "NAME"=>"Bottom Menu")
);?>
<?endif;?>
<?
#DebugMessage($arResult);
?>