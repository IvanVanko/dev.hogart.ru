<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?if (count($arResult['ITEMS']) > 0):?>
<ul class="inner_menu menu_animation big-menu height-auto">
<?foreach ($arResult["ITEMS"] as $arItem): //Цикл по всем элементам?>
<?
#DebugMessage($arItem);
?>
	<li><a href="#main_slide" class="slide-trigger" data-effect="mainMenuAnimation"><?=$arItem['NAME']?></a>
		<div class="menu_inner inner_block content-text inner_block_content">
			<?#DebugMessage($arItem["PROPERTIES"]["duties"]);?>
			<?if(strlen($arItem["PROPERTIES"]["duties"]["VALUE"]["TEXT"]) > 0):?>
			<h2><?=$arItem["PROPERTIES"]["duties"]["NAME"]?></h2>
			<?=$arItem["PROPERTIES"]["duties"]["~VALUE"]["TEXT"]?>
			<?endif;?>
			<?if(strlen($arItem["PROPERTIES"]["demands"]["VALUE"]["TEXT"]) > 0):?>
			<h2><?=$arItem["PROPERTIES"]["demands"]["NAME"]?></h2>
			<?=$arItem["PROPERTIES"]["demands"]["~VALUE"]["TEXT"]?>
			<?endif;?>
			<?if(strlen($arItem["PROPERTIES"]["conditions"]["VALUE"]["TEXT"]) > 0):?>
			<h2><?=$arItem["PROPERTIES"]["conditions"]["NAME"]?></h2>
			<?=$arItem["PROPERTIES"]["conditions"]["~VALUE"]["TEXT"]?>
			<?endif;?>
			<?if(strlen($arItem["PROPERTIES"]["salary"]["VALUE"]) > 0):?>
			<div class="salary"><?=$arItem["PROPERTIES"]["salary"]["VALUE"]?> рублей</div>
			<?endif;?>
		</div>              
	</li>
<?endforeach;?>
</ul>
<?endif; ?>

					
<!--<aside class="sidebar js-fh js-fixed-block js-paralax-height" data-fixed="top">
	<div class="inner js-paralax-item">
		<div class="padding">
			<h2>Вакансии</h2>
			<?if (count($arResult['ITEMS']) > 0):?>
				<ul class="history-vac">
					<?foreach ($arResult['ITEMS'] as $arItem):?>
						<li><a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem['NAME']?></a></li>
					<?endforeach;?>
				</ul>
			<?endif;?>
		</div>
	</div>
</aside>/-->