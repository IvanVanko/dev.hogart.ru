<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

?>
<div class="item-inner">
	<h1><?=$arResult["NAME"]?></h1>
 <?
 #DebugMessage($arResult["DISPLAY_PROPERTIES"]["photos"]);
 ?>
				<? if (count($arResult["DISPLAY_PROPERTIES"]["photos"]["VALUE"]) > 0): ?>
	<div class="detail-slider">
		<div class="owl-carousel center-controls" data-pagination="false">
			<? if (!empty($arResult['DETAIL_PICTURE']['SRC'])) : ?>
			<div class="detail-slide">
				<?$photo_small = CFile::ResizeImageGet($arResult['DETAIL_PICTURE'], array("width" =>150, "height" =>150 ), BX_RESIZE_IMAGE_EXACT, true);?>
				<a href="<?=$arResult['DETAIL_PICTURE']['SRC']?>" class="open-img"><img src="<?= $photo_small['src'] ?>" width="150px;"></a>
			</div>
			<?endif?>
			<? foreach ($arResult["DISPLAY_PROPERTIES"]["photos"]["VALUE"] as $key => $photo): ?>
			<div class="detail-slide">
				<?$photo_small = CFile::ResizeImageGet($photo, array("width" => 150, "height" => 150), BX_RESIZE_IMAGE_EXACT, true);?>
				<a href="<?=$arResult["DISPLAY_PROPERTIES"]["photos"]["FILE_VALUE"][$key]['SRC']?>" class="open-img"><img src="<?= $photo_small['src'] ?>"  width="150px;"></a>
			</div>
			<?endforeach?>
		</div>
	</div>
	<?endif;?>


	<div class="detail-description">
		<? if ($USER->IsAuthorized() && !empty($arResult["PRICES"]["BASE"]["DISCOUNT_DIFF_PERCENT"])): ?>
		<div class="price">
			<?= number_format($arResult["CATALOG_PRICE_1"],0,'.'," ") ?> Р
			<?#=HogartHelpers::wPrice($arResult["PRICES"]["BASE"]["PRINT_DISCOUNT_VALUE"]);?>
		</div>
		<? else: ?>
		<div class="price">
			<?= number_format($arResult["CATALOG_PRICE_1"],0,'.'," ") ?> Р
									<?#=HogartHelpers::wPrice($arResult["PRICES"]["BASE"]["PRINT_VALUE"]);?>
								</div>
								<?endif;?>
		<!--<div class="price"><?= number_format($arResult["CATALOG_PRICE_1"],0,'.'," ") ?> Р</div>/-->
		<? if (!empty($arResult["DISPLAY_PROPERTIES"]["sku"]["VALUE"])): ?>
				<div class="item_title">Артикул: <?= $arResult["DISPLAY_PROPERTIES"]["sku"]["VALUE"] ?></div>
		<? endif; ?>
		<div class="detail_info">
			<? if ($arResult["CATALOG_QUANTITY"] > 0): ?>
				В наличии<? if ($USER->IsAuthorized()): ?><span><?= $arResult["CATALOG_QUANTITY"]; ?> шт.</span><? endif; ?>
			<? else: ?>
				Под заказ<br />
				<? if (!empty($arResult["PROPERTIES"]["delivery_period"]["VALUE"])): ?>
				<br>
				Срок поставки <?= $arResult["PROPERTIES"]["delivery_period"]["VALUE"] ?> <?= number($arResult["PROPERTIES"]["delivery_period"]["VALUE"], array('день', 'дня', 'дней')) ?>
				<? endif; ?>
				<? if (!empty($arResult["PROPERTIES"]["supply"]["VALUE"])): ?>
					ожидаемое поступление <br>
					<?= FormatDate('d F', MakeTimeStamp($arResult["PROPERTIES"]["supply"]["VALUE"])); ?>
				<?endif; ?>
			<?endif; ?>

			<div class="detail_content">
				<dl class="detail-content-list">
					<? if ($arResult["CUSTOM"]["BRAND_NAME"]) : ?>
					<dt><?= $arResult["DISPLAY_PROPERTIES"]["brand"]["NAME"] ?></dt>
									<dd><?= $arResult["CUSTOM"]["BRAND_NAME"] ?></dd>
					<?endif;?>
					<?if (!empty($arResult['DISPLAY_PROPERTIES']['collection']['LINK_ELEMENT_VALUE'])):
								$collection_element = current($arResult['DISPLAY_PROPERTIES']['collection']['LINK_ELEMENT_VALUE'])?>
					<dt><?= $arResult['DISPLAY_PROPERTIES']['collection']['NAME'] ?></dt>
					<dd><?= $collection_element['NAME'] ?></dd>
					<?endif;?>
					<?$arShownProperties = array();?>
					<?$arHiddenProperties = array();?>
					<?foreach ($arResult["PROPERTIES"] as $arProperty)
					{
						if (!empty($arProperty["VALUE"]) && $arProperty['CODE'] != 'brand' &&  $arProperty['CODE'] != 'collection')
						{
							if ($arProperty["DISPLAY_EXPANDED"] == 'Y') 
							{
								$arShownProperties[] = $arProperty;
							} 
							else
							{
								$arHiddenProperties[] = $arProperty;
							}
						}
					}?>
					<?if (!empty($arShownProperties)) :?>
						<? foreach ($arShownProperties as $propertyName => $arProperty): ?>
						<dt><?= $arProperty["NAME"] ?> </dt>
						<dd><?= $arProperty["VALUE"] ?></dd>
						<? endforeach; ?>					
					<?endif;?>
					<?if (!empty($arHiddenProperties)) :?>
						<? foreach ($arHiddenProperties as $propertyName => $arProperty): ?>
						<dt><?= $arProperty["NAME"] ?> </dt>
						<dd><?= $arProperty["VALUE"] ?></dd>
						<? endforeach; ?>					
					<?endif;?>					
				</dl>
			</div>
		</div>
	</div>
</div>
<a href="#" class="btn link-btn list-icon">Прайс лист на продукцию</a>
<!--<a href="/documentation/" class="btn link-btn cat-icon">Документация</a>/-->

<? if (count($arResult["DOCS"]) > 0): ?>
	<a href="#" class="btn link-btn cat-icon all open-next" onClick="$('.result-item').css('display','block')">Документация</a>
	<div class="detail-text content-text">
		<div class="result-item" style="display:none;">
			<?foreach ($arResult["DOCS"] as $arDocument):?>
				<div class="brand-doc-name">
					<div class="item-title">
						<a href="<?=$arDocument["FILE"]["SRC"] ?>" target="_blank"> <?= $arDocument["NAME"] ?></a>
						<span class="size"><?= $arDocument["FILE"]["EXTENTION"] ?>, <?= $arDocument["FILE"]["FILE_SIZE"] ?> mb</span>
					</div>
				</div>
			<?endforeach;?>
		</div>
	</div>
<? endif; ?>


<div class="detail-text content-text">
	<?= $arResult["DETAIL_TEXT"] ?>
</div>

<? if (!empty($arResult["PROPERTIES"]['video_youtube']['~VALUE'])): ?>
	<div class="video_wrap">
		<iframe width="100%" height="196" src="https://www.youtube.com/embed/<?= $arResult["PROPERTIES"]['video_youtube']['VALUE'] ?>?rel=0" frameborder="0" allowfullscreen></iframe>
	</div>
<? endif; ?>

<? if (isset($arResult["buy_with_this"]) || isset($arResult["related"]) || isset($arResult["this_collection"])): ?>
	<select class="arrow-select title-with-arrow choose-block-trigger">
	<? if (isset($arResult["buy_with_this"])): ?>
		<option value="block_1">С этим товаром покупают</option>
	<?endif;?>
	<? if (isset($arResult["related"])): ?>
		<option value="block_2">Сопуствующие товары</option>
	<?endif;?>
	<? if (isset($arResult["this_collection"])): ?>
		<option value="block_3" selected>Еще из этой коллекции</option>
	<?endif;?>
	<? if (isset($arResult["alternative"])): ?>
		<option value="block_4" selected>Альтернативные товары</option>
	<?endif;?>
	</select>
<?endif;?>	

<? if (isset($arResult["buy_with_this"])): ?>
	<div class="similar-items choose-items block_1">
		<? foreach ($arResult["buy_with_this"] as $key => $arProduct): ?>
		<div class="one_item">
			<?if (!empty($arProduct["PREVIEW_PICTURE"])) 
			{
				$file = CFile::ResizeImageGet($arProduct["PREVIEW_PICTURE"],
				array('width' =>108, 'height' => 108), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
				$file = $file['src'];
			} 
			elseif (!empty($arProduct["PROPERTY_PHOTOS_VALUE"])) 
			{
				$file = CFile::ResizeImageGet($arProduct["PROPERTY_PHOTOS_VALUE"],
				array('width' => 108, 'height' => 108), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
				$file = $file['src'];
			} 
			else 
			{
				$file = '/images/project_no_img.jpg';
			}?>
			<div class="item-img-wrap"><img src="<?=$file?>" width="71"></div>
			<div class="price"><?=number_format($arProduct["CATALOG_PRICE_1"],0,'.'," ") ?> Р</div>
			<div class="item_description">
				 <a href="<?= $arProduct["DETAIL_PAGE_URL"] ?>"><span class="item_title"><?=$arProduct["NAME"]?></span></a>
				<div class="item_info">
					<? if (!empty($arProduct["PROPERTY_SKU_VALUE"])): ?>
						Артикул: <?= $arProduct["PROPERTY_SKU_VALUE"] ?>
					<? endif; ?>
					Под заказ. Срок поставки 3 дн
				</div>
				<div class="item_body">
					<dl class="clearfix">
						<dt>Бренд</dt>
						<dd class="pr"><?=$arProduct['BRAND_NAME']?></dd>
					</dl>
					<?if (strlen($arProduct['COLLECTION_NAME']) > 0):?>
					<dl class="clearfix">
						<dt>Коллекция</dt>
						<dd class="pr"><?=$arProduct['COLLECTION_NAME']?></dd>
					</dl>
					<?endif;?>
				</div>
			</div>
		</div>
		<?endforeach;?>
	</div>
<? endif; ?>	

<? if (isset($arResult["related"])): ?>
	<div class="similar-items choose-items block_2">
		<? foreach ($arResult["related"] as $key => $arProduct): ?>
			<?#DebugMessage($arProduct);?>
			<div class="one_item">
				<?if (!empty($arProduct["PREVIEW_PICTURE"])) 
				{
					$file = CFile::ResizeImageGet($arProduct["PREVIEW_PICTURE"],
					array('width' => 108, 'height' => 108), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
					$file = $file['src'];
				} 
				elseif (!empty($arProduct["PROPERTY_PHOTOS_VALUE"])) 
				{
					$file = CFile::ResizeImageGet($arProduct["PROPERTY_PHOTOS_VALUE"],
					array('width' => 108, 'height' => 108), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
					$file = $file['src'];
				} 
				else 
				{
					$file = '/images/project_no_img.jpg';
				}?>
				<div class="item-img-wrap"><img src="<?=$file?>" width="71"></div>
				<div class="price"><?=number_format($arProduct["CATALOG_PRICE_1"],0,'.'," ") ?> Р</div>
				<div class="item_description">
								 <a href="<?= $arProduct["DETAIL_PAGE_URL"] ?>"><span class="item_title"><?=$arProduct["NAME"]?></span></a>
					<div class="item_info">
						<? if (!empty($arProduct["PROPERTY_SKU_VALUE"])): ?>
							Артикул: <?= $arProduct["PROPERTY_SKU_VALUE"] ?>
						<? endif; ?>
						Под заказ. Срок поставки 3 дн
					</div>
					<div class="item_body">
						<dl class="clearfix">
							<dt>Бренд</dt>
							<dd class="pr"><?=$arProduct['BRAND_NAME']?></dd>
						</dl>
						<?if (strlen($arProduct['COLLECTION_NAME']) > 0):?>
						<dl class="clearfix">
							<dt>Коллекция</dt>
							<dd class="pr"><?=$arProduct['COLLECTION_NAME']?></dd>
						</dl>
						<?endif;?>
					</div>
				</div>
			</div>
		<?endforeach;?>
	</div>
<? endif; ?>


<? if (isset($arResult["this_collection"])): ?>
	<div class="similar-items choose-items block_3 opened">
		<? foreach ($arResult["this_collection"] as $key => $arProduct): ?>
			<?#DebugMessage( $arProduct);?>
			<div class="one_item">
				<?if (!empty($arProduct["PREVIEW_PICTURE"])) 
				{
					$file = CFile::ResizeImageGet($arProduct["PREVIEW_PICTURE"],
					array('width' =>108, 'height' => 108), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
					$file = $file['src'];
				} 
				elseif (!empty($arProduct["PROPERTY_PHOTOS_VALUE"])) 
				{
					$file = CFile::ResizeImageGet($arProduct["PROPERTY_PHOTOS_VALUE"],
					array('width' => 108, 'height' => 108), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
					$file = $file['src'];
				} 
				else 
				{
					$file = '/images/project_no_img.jpg';
				}?>
				<div class="item-img-wrap"><img src="<?=$file?>" width="71"></div>
				<div class="price"><?=number_format($arProduct["CATALOG_PRICE_1"],0,'.'," ") ?> Р</div>
				<div class="item_description">
								 <a href="<?= $arProduct["DETAIL_PAGE_URL"] ?>"><span class="item_title"><?=$arProduct["NAME"]?></span></a>
					<div class="item_info">
						<? if (!empty($arProduct["PROPERTY_SKU_VALUE"])): ?>
						Артикул: <?= $arProduct["PROPERTY_SKU_VALUE"] ?>
						<? endif; ?>
						Под заказ. Срок поставки 3 дн
					</div>
					<div class="item_body">
						<dl class="clearfix">
							<dt>Бренд</dt>
							<dd class="pr"><?=$arProduct['BRAND_NAME']?></dd>
						</dl>
						<?if (strlen($arProduct['COLLECTION_NAME']) > 0):?>
						<dl class="clearfix">
							<dt>Коллекция</dt>
							<dd class="pr"><?=$arProduct['COLLECTION_NAME']?></dd>
						</dl>
						<?endif;?>
					</div>
				</div>
			</div>
		<?endforeach;?>
	</div>
<? endif; ?>

<? if (isset($arResult["alternative"])): ?>
	<div class="similar-items choose-items block_4 opened">
		<? foreach ($arResult["alternative"] as $key => $arProduct): ?>
			<?#DebugMessage( $arProduct);?>
			<div class="one_item">
				<?if (!empty($arProduct["PREVIEW_PICTURE"])) 
				{
					$file = CFile::ResizeImageGet($arProduct["PREVIEW_PICTURE"],
					array('width' =>108, 'height' => 108), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
					$file = $file['src'];
				} 
				elseif (!empty($arProduct["PROPERTY_PHOTOS_VALUE"])) 
				{
					$file = CFile::ResizeImageGet($arProduct["PROPERTY_PHOTOS_VALUE"],
					array('width' => 108, 'height' => 108), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
					$file = $file['src'];
				} 
				else 
				{
					$file = '/images/project_no_img.jpg';
				}?>
				<div class="item-img-wrap"><img src="<?=$file?>" width="71"></div>
				<div class="price"><?=number_format($arProduct["CATALOG_PRICE_1"],0,'.'," ") ?> Р</div>
				<div class="item_description">
								 <a href="<?= $arProduct["DETAIL_PAGE_URL"] ?>"><span class="item_title"><?=$arProduct["NAME"]?></span></a>
					<div class="item_info">
						<? if (!empty($arProduct["PROPERTY_SKU_VALUE"])): ?>
						Артикул: <?= $arProduct["PROPERTY_SKU_VALUE"] ?>
						<? endif; ?>
						Под заказ. Срок поставки 3 дн
					</div>
					<div class="item_body">
						<dl class="clearfix">
							<dt>Бренд</dt>
							<dd class="pr"><?=$arProduct['BRAND_NAME']?></dd>
						</dl>
						<?if (strlen($arProduct['COLLECTION_NAME']) > 0):?>
						<dl class="clearfix">
							<dt>Коллекция</dt>
							<dd class="pr"><?=$arProduct['COLLECTION_NAME']?></dd>
						</dl>
						<?endif;?>
					</div>
				</div>
			</div>
		<?endforeach;?>
	</div>
<? endif; ?>

<?
#DebugMessage($arResult);
?>