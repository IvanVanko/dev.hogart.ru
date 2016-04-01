<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?if (count($arResult["ITEMS"]) > 0):?>
<ul class="brands-list">
	<?foreach ($arResult["ITEMS"] as $arItem): ?>
	<li class="brand">
		<a href="<?=$arItem['DETAIL_PAGE_URL']?>">
			<?if (strlen($arItem['PREVIEW_PICTURE']['SRC']) > 0):?>
			<div class="img-wrap">
				<?#$file = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE']['ID'], array("width" => 160, "height" => 90), BX_RESIZE_IMAGE_EXACT);?>
				<img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="<?=htmlspecialcharsbx($arItem['NAME']);?>">
			</div>
			<?endif;?>
			<span class="brand-name"><?=$arItem['NAME'];?></span>
		</a>
	</li>
	<?endforeach;?>
</ul>	
<a href="/brands/" class="btn link-btn arrow-icon">ПОКАЗАТЬ ЕЩЕ бренды</a>
<?endif; ?>
<?#DebugMessage($arResult);?>
