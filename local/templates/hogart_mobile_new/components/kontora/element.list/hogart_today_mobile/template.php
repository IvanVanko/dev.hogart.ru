<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? if (count($arResult['ITEMS']) > 0): ?>

<div class="one-news-slider">
	<div    class="owl-carousel" data-pagination="false">
	<? foreach ($arResult['ITEMS'] as $key => $arItem): ?>
		<div class="item" style="text-align: center;">
			<iframe width="100%" height="100%"	src="https://www.youtube.com/embed/<?= $arItem["PROPERTIES"]['video']['VALUE'] ?>?rel=0" frameborder="0" allowfullscreen></iframe>
		</div>
	<?endforeach;?>
	</div>
</div>
<? endif; ?>