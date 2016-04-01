<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="side_href">
    <ul class="search-category-links js-menu-select">
		<?$depth_level = 0;
		foreach ($arResult['SECTIONS'] as &$arSection):?>
			<?if ($depth_level != 0):?>
				<?if ($depth_level == $arSection['RELATIVE_DEPTH_LEVEL']):?>
					</li>
				<?elseif ($arSection['RELATIVE_DEPTH_LEVEL'] > $depth_level):?>
					<ul class="sub-menu">
				<?elseif ($arSection['RELATIVE_DEPTH_LEVEL'] < $depth_level):?>
					<?=str_repeat('</li></ul>', $depth_level - $arSection['RELATIVE_DEPTH_LEVEL'])?></li>
				<?endif;?>
			<?endif;?>
			<li><a<?if ($_REQUEST['section_id'] == $arSection['ID']):?> class="current-page"<?endif;?> href="?section_id=<?=$arSection['ID']?>&q=<?=$_REQUEST['q']?>"><?=$arSection['NAME']?> (<?=$arSection["ELEMENT_CNT"];?>)</a>
		<?$depth_level = $arSection['RELATIVE_DEPTH_LEVEL'];
		endforeach;?>
	</ul>
</div>