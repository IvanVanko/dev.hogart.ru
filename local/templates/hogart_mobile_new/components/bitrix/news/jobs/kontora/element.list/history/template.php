<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?if (count($arResult['ITEMS']) > 0):?>
<div class="list_event content-text">
	<h2>Истори успеха</h2>
	<?foreach ($arResult["ITEMS"] as $arItem): ?>
	<div class="event-person clearfix">
		<div class="person_photo">
			<img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>">
		</div>
		<div class="person-info">
			<h3><a href="/history/"><?=$arItem['NAME']?><br /><?=$arItem['PROPERTIES']['name']['VALUE']?></a></h3>
			<p><?=$arItem['PROPERTIES']['post']['VALUE']?></p>
		</div>
	</div>
	<?endforeach;?>
</div>
<a href="/history/" class="btn link-btn arrow-icon">все истории успеха</a>
<?endif; ?>

