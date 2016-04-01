<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); 
$mainItem = array();?>
<?#DebugMessage($arResult['ITEMS']);?>

<?if(count($arResult['ITEMS']) > 0 && is_array($arResult['ITEMS'])):?>
<section class="slide hide" id="map_slide">
<ul class="main_menu big-menu map-menu menu_animation_2 height-auto">
	<?foreach ($arResult['ITEMS'] as $key => $arItem):?>
	<?$coords = explode(',',$arItem['PROPERTIES']['map']['VALUE']);?>
	<?if ($key == 0) 
	{
		$active = 'class="active"'; 
		$active1=' active map_active';
	} 
	else 
	{
		$active = $active1 = '';
	}?>
	<?#DebugMessage($arItem['PROPERTIES']['map']['VALUE']);?>
	<li <?= $active?> data-effect="contactMenuAnimation"><a href="#map_slide" class="slide-trigger <?=$active1?>" data-effect="contactMenuAnimation" data-val="<?=$arItem['PROPERTIES']['map']['VALUE']?>"><?=$arItem["NAME"]?></a>
		<div class="inner_block menu_inner">
			<small>склад и сервис</small>
			<div class="contacts-office">
				<div class="address-block">
					<?=$arItem['PROPERTIES']['address']['VALUE'] ?>
				</div>
				<!--<div class="time-block">
					 00:00 - 00:00
				</div>/-->
				<?if (!empty($arItem['PROPERTIES']['phone']['VALUE'])):?>
					<?if (is_array($arItem['PROPERTIES']['phone']['VALUE'])):?>
					<div class="tel-block">
						<?foreach ($arItem['PROPERTIES']['phone']['VALUE'] as $k => $v):?>					
						<a href="tel:<?=$v?>"><?=$v?></a>
						<?endforeach;?>
					</div>
					<?endif;?>
				<?endif;?>
				<?if (!empty($arItem['PROPERTIES']['mail']['VALUE'])):?>
					<?if (is_array($arItem['PROPERTIES']['mail']['VALUE'])):?>
					<div class="email-block">
						<?foreach ($arItem['PROPERTIES']['mail']['VALUE'] as $k => $v):?>					
						<a href="mailto:<?=$v?>"><?=$v?></a>
						<?endforeach;?>
					</div>
					<?endif;?>
				<?endif;?>				
			</div>
			<?if ($key == 0):?>
			<div class="map-contacts-wrap">
				<a href="http://maps.google.com/maps?daddr=<?=$arItem['PROPERTIES']['map']['VALUE']?>" class="icon_1"></a>
				<a href="http://maps.google.com/maps?daddr=<?=$arItem['PROPERTIES']['map']['VALUE']?>" class="icon_2"></a>
				<div class="contacts-map" id="map_canvas"></div>
			</div>
			<?endif;?>
		</div>  
	</li>
	<?endforeach;?>
</ul>
</section>
<?endif;?>
<?#DebugMessage($arResult['ITEMS']);?>
